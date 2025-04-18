<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use App\Models\Specialty;
use App\Models\Classification;
use App\Models\Procedure;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory as WordReader;
use PhpOfficez\PhpSpreadsheet\IOFactory as ExcelReader;
use Illuminate\Support\Str;

class ManualController extends Controller
{
    //診療科(specialties)一覧のビューを表示する
    public function specialtyIndex()
    {
        //全診療科を取得（例：内科、外科など）
        $specialties = Specialty::all();
        return view('manuals.specialties.index', compact('specialties'));
    }

    // 検索機能（マニュアルタイトルで検索）
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $manuals = Manual::with(['procedure.classification.specialty'])
            ->where('title', 'like', "%{$keyword}%")
            ->orWhereHas('procedure', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orWhereHas('procedure.classification', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orWhereHas('procedure.classification.specialty', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->get();

        return view('manuals.search_results', compact('manuals', 'keyword'));
    }

    //選択した診療科に基づく分類(classifications)一覧のビューを表示する
    public function classificationIndex($specialtyId)
    {
        //選択された診療科を取得
        $specialty = Specialty::findOrFail($specialtyId);
        //選択された診療科に紐づく分類を取得(specialtyモデルにclassifications()のリレーションが定義されている前提)
        $classifications = $specialty->classifications;
        return view('manuals.classifications.index', compact('specialty', 'classifications'));
    }

    //選択した分類に基づく術式(procedures)一覧のビューを表示する
    public function procedureIndex($specialtyId, $classificationId)
    {
        //分類と関連する診療科を取得
        $specialty = Specialty::findOrFail($specialtyId);
        //分類と関連する術式を取得
        $classification = Classification::findOrFail($classificationId);
        //Classificationモデルにprocedures()のリレーションが定義されている前提
        $procedures = $classification->procedures;
        return view('manuals.procedures.index', compact('specialty','classification', 'procedures'));
    }

    //マニュアルの詳細画面を表示する
    public function show($manualId)
    {
        $manual = Manual::findOrFail($manualId);
        //マニュアルに関連する診療科、分類、術式を取得
        $procedure = $manual->procedure;
        $classification = $procedure->classification;
        $specialty = $classification->specialty;
        return view('manuals.show', compact('manual', 'procedure', 'classification', 'specialty'));
    }

    //マニュアルの新規作成画面を表示する
    public function create()
    {
        //新規作成フォームで診療科一覧などを表示するためのデータを取得
        $specialties = Specialty::all();
        return view('manuals.create', compact('specialties'));
    }

    //マニュアルの新規作成処理を行う
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'classification_id' => 'required|exists:classifications,id',
            'procedure_id' => 'required|exists:procedures,id',
            'files' => 'nullable|array',
            'files.*' => 'nullable|url',
            'editable_files' => 'nullable|array',
            'editable_files.*.name' => 'nullable|string|max:255',
            'editable_files.*.url' => 'nullable|url',
            'editable_files.*.view_url' => 'nullable|url',
        ]);

        // ファイル情報を処理（名前を自動取得）
        $viewOnlyFiles = [];
        $editableFiles = [];
        foreach ($validated['editable_files'] ?? [] as $file) {
            $name = $file['name'] ?? '';
            $url = $file['url'] ?? '';
            $viewUrl = $file['view_url'] ?? '';

            if (empty($url)) {
                continue;
            }

            if (empty($name)) {
                $name = basename(parse_url($url, PHP_URL_PATH));
            }

            $editableFiles[] = [
                'name' => $name,
                'url' => $url,
            ];

            if (!empty($viewUrl)) {
                $viewOnlyFiles[] = [
                    'name' => $name,
                    'url' => $viewUrl,
                ];
            }
        }

        $userHospital = auth()->user()->userHospital;

        // すでに該当procedure_idのマニュアルがある場合はマージ・更新
        $existingManual = Manual::where('procedure_id', $validated['procedure_id'])->first();

        if ($existingManual) {
            // editable_files と files を既存とマージ
            $currentEditable = json_decode($existingManual->editable_files, true) ?? [];
            $currentViewOnly = json_decode($existingManual->files, true) ?? [];

            $mergedEditable = array_merge($currentEditable, $editableFiles);
            $mergedViewOnly = array_merge($currentViewOnly, $viewOnlyFiles);

            $existingManual->update([
                'title' => $validated['title'], // 必要なら上書き
                'updated_by' => auth()->id(),
                'editable_files' => json_encode($mergedEditable),
                'files' => json_encode($mergedViewOnly),
            ]);

            return redirect()->route('manuals.show', $existingManual->id)->with('success', '既存のマニュアルに追記しました！');
        }

        // 新しいマニュアルを作成
        Manual::create([
            'title' => $validated['title'],
            'content' => '', // 必要なら追加
            'hospital_id' => $userHospital?->hospital_id,
            'department_id' => $userHospital?->department_id,
            'specialty_id' => $validated['specialty_id'],
            'classification_id' => $validated['classification_id'],
            'procedure_id' => $validated['procedure_id'],
            'version' => 1.0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'files' => json_encode($viewOnlyFiles),
            'editable_files' => json_encode($editableFiles),
        ]);

        return redirect()->route('manuals.index')->with('success', '新しいマニュアルを作成しました！');
    }    

    //マニュアルの編集画面を表示する
    public function edit($manualId)
    {
        $manual = Manual::findOrFail($manualId);
        //編集フォームで診療科一覧などを表示するためのデータを取得
        $specialties = Specialty::all();
        $manual->files_array = json_decode($manual->files, true) ?? [];
        return view('manuals.edit', compact('manual', 'specialties'));
    }

    //マニュアルの更新処理を行う

    public function update(Request $request, $manualId)
    {
        // バリデーションルールを定義
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'specialty_id' => 'nullable|integer|exists:specialties,id',
            'classification_id' => 'nullable|integer|exists:classifications,id',
            'procedure_id' => 'nullable|integer|exists:procedures,id',
            'editable_files' => 'nullable|array',
            'editable_files.*.name' => 'nullable|string',
            'editable_files.*.url' => 'nullable|url',
            'editable_files.*.view_url' => 'nullable|url',
        ]);

        // 更新対象のマニュアルを取得
        $manual = Manual::findOrFail($manualId);

        // ファイル情報を処理：editable_filesからview_urlを抽出
        $viewOnlyFiles = [];
        foreach ($validated['editable_files'] ?? [] as $file) {
            if (!empty($file['name']) && !empty($file['view_url'])) {
                $viewOnlyFiles[] = [
                    'name' => $file['name'],
                    'url' => $file['view_url'],
                ];
            }
        }
        
        $editableFiles = [];
        foreach ($validated['editable_files'] as $file) {
            $name = $file['name'] ?? '';
            if (empty($name) && !empty($file['url'])) {
                $name = basename(parse_url($file['url'], PHP_URL_PATH));
            }

            if (!empty($name) && !empty($file['url'])) {
                $editableFiles[] = [
                    'name' => $name,
                    'url' => $file['url'],
                ];
            }
        }

        // マニュアル情報を更新
        $manual->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? $manual->content,
            'specialty_id' => $validated['specialty_id'] ?? $manual->specialty_id,
            'classification_id' => $validated['classification_id'] ?? $manual->classification_id,
            'procedure_id' => $validated['procedure_id'] ?? $manual->procedure_id,
            'updated_by' => $request->user()->id,
            'files' => json_encode($viewOnlyFiles), // JSON形式で保存
            'editable_files' => json_encode($editableFiles), // JSON形式で保存
        ]);

        // マニュアルの詳細画面にリダイレクト
        return redirect()->route('manuals.show', $manual->id)
                         ->with('success', 'マニュアルを更新しました');
    }

    //マニュアルの削除処理を行う
    public function destroy($manualId)
    {
        //削除対象のマニュアルを取得
        $manual = Manual::findOrFail($manualId);
        $manual->delete();
        //マニュアル一覧画面にリダイレクト
        return redirect()->route('manuals.specialty.index')
                         ->with('success', 'マニュアルを削除しました');
    }

    //マニュアルの削除前に確認画面を表示する
    public function confirmDelete(Manual $manual)
    {
        $manual = Manual::findOrFail($manualId);
        //確認画面用のビューを返す
        return view('manuals.delete', compact('manual'));
    }

    // 診療科ごとの分類(classifications)を取得するAPI（JSONレスポンス）
    public function getClassifications($specialtyId)
    {
        $specialty = Specialty::findOrFail($specialtyId);
        $classifications = $specialty->classifications;

        return response()->json($classifications);
    }

    // 分類ごとの術式(procedures)を取得するAPI（JSONレスポンス）
    public function getProcedures($classificationId)
    {
        $classification = Classification::findOrFail($classificationId);
        $procedures = $classification->procedures;

        return response()->json($procedures);
    }

    // 術式に紐づくマニュアルのタイトルを取得するAPI（JSONレスポンス）
    public function getTitleByProcedure($procedureId)
    {
        $manual = Manual::where('procedure_id', $procedureId)->first();

        if ($manual) {
            return response()->json(['title' => $manual->title]);
        }

        return response()->json(['title' => null]);
    }

    // マニュアルファイルの内容を抽出してテキストとして返す（Word / Excel 対応）
    public function extractTextFromFile($url)
    {
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        dd($fileName);//確認用
        $filePath = storage_path('app/public/manuals/' . $fileName);

        if (!file_exists($filePath)) {
            return 'ファイルが見つかりません。';
        }

        $extension = Str::lower(pathinfo($filePath, PATHINFO_EXTENSION));
        $text = '';

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
                $text = '対応していないファイル形式です。';
            }
        } catch (\Exception $e) {
            $text = 'エラーが発生しました：' . $e->getMessage();
        }

        return $text;
    }

    public function previewText(Manual $manual)
    {
        $files = json_decode($manual->files, true);
        if (!$files || count($files) === 0) {
            return 'ファイルがありません。';
        }

        // とりあえず最初のファイルを読み込んでみる
        $fileUrl = $files[0]['url'] ?? null;

        if (!$fileUrl) {
            return 'ファイルURLが見つかりません';
        }

        $text = $this->extractTextFromFile($fileUrl);

        // ブラウザで読みやすく改行とエスケープ処理
        return nl2br(e($text));
    }
}
