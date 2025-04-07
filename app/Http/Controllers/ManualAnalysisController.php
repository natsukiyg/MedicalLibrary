<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory as WordReader;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelReader;
use OpenAI\Laravel\Facades\OpenAI; // ← Laravelでopenai/api使用中の場合
use Illuminate\Support\Str;
use App\Models\ManualFile;
use App\Models\AiAnalysis;

class ManualAnalysisController extends Controller
{
    public function analyze(Request $request, $manualId)
    {
        $request->validate([
            'file' => 'file|mimes:docx,xlsx|max:10240', // 10MBまで
        ]);

        $manualDir = storage_path("app/manuals/{$manualId}");
        if (!file_exists($manualDir)) {
            mkdir($manualDir, 0777, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = Str::lower($file->getClientOriginalExtension());
            $filePath = "{$manualDir}/latest.{$extension}";
            $file->move($manualDir, "latest.{$extension}");

            \App\Models\ManualFile::create([
                'manual_id' => $manualId,
                'file_path' => "manuals/{$manualId}/latest.{$extension}",
                'file_type' => $extension,
            ]);
        } else {
            // 拡張子はdocx優先で存在確認（なければxlsx）
            $filePath = file_exists("{$manualDir}/latest.docx")
                ? "{$manualDir}/latest.docx"
                : (file_exists("{$manualDir}/latest.xlsx") ? "{$manualDir}/latest.xlsx" : null);

            if (!$filePath) {
                return back()->with('error', '解析対象のファイルが見つかりません。アップロードしてください。');
            }

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        }

        $text = '';
        $text = preg_replace('/\n{2,}/', "\n", $text); // 連続改行を1つに
        $text = trim($text); // 先頭末尾の空白除去

        try {
            if ($extension === 'docx') {
                $phpWord = WordReader::load($filePath);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
            } elseif ($extension === 'xlsx') {
                $spreadsheet = ExcelReader::load($filePath);
                foreach ($spreadsheet->getAllSheets() as $sheet) {
                    foreach ($sheet->toArray() as $row) {
                        $text .= implode(' ', $row) . "\n";
                    }
                }
            } else {
                return back()->with('error', '対応していないファイル形式です。');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'ファイルの読み取り中にエラーが発生しました: ' . $e->getMessage());
        }

        // ✅ OpenAIに送信（まずはシンプルなプロンプト）
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', //いずれ有料版でGPT-4に変更予定
            'messages' => [
                ['role' => 'system', 'content' => 'あなたは医療マニュアルの要約・ガイドライン提案を行うAIです。'],
                ['role' => 'user', 'content' => "以下は医療従事者向けに作成されたマニュアルです。内容を正確かつ簡潔に要約してください。さらに、関連する医学的なガイドライン・参考文献・注意事項・推奨される実践方法についても具体的に提示してください。可能であれば参照URLも日本語または英語で含めてください。\n\n" . $text],
            ],
        ]);

        $aiReply = $response->choices[0]->message->content ?? 'AIからの返信が取得できませんでした。';

        \App\Models\AiAnalysis::create([
            'manual_id' => $manualId,
            'role' => 'user',
            'content' => 'このマニュアルを解析してください。',
        ]);
        \App\Models\AiAnalysis::create([
            'manual_id' => $manualId,
            'role' => 'assistant',
            'content' => $aiReply,
        ]);

        return redirect()->route('manuals.show', ['manual' => $manualId]);
    }

    public function analyzeFollowup(Request $request, $manualId)
    {
        $request->validate([
            'followup' => 'required|string|max:2000',
        ]);

        $question = $request->input('followup');

        $previousMessages = \App\Models\AiAnalysis::where('manual_id', $manualId)->orderBy('created_at')->get();

        $messages = [
            ['role' => 'system', 'content' => 'あなたは親切な医療AIアシスタントです。']
        ];

        foreach ($previousMessages as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
        ]);

        $aiReply = $response->choices[0]->message->content ?? 'AIの返答が取得できませんでした。';

        \App\Models\AiAnalysis::create([
            'manual_id' => $manualId,
            'role' => 'user',
            'content' => $question,
        ]);
        \App\Models\AiAnalysis::create([
            'manual_id' => $manualId,
            'role' => 'assistant',
            'content' => $aiReply,
        ]);

        return redirect()->route('manuals.show', ['manual' => $manualId]);
    }

    public function destroy(AiAnalysis $analysis)
    {
        $analysis->delete();
        return redirect()->back()->with('success', 'AIの解析履歴を削除しました。');
    }
}
