<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use App\Models\Specialty;
use App\Models\Classification;
use App\Models\Procedure;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    //診療科(specialties)一覧のビューを表示する
    public function specialtyIndex()
    {
        //全診療科を取得（例：内科、外科など）
        $specialties = Specialty::all();
        return view('manuals.specialties.index', compact('specialties'));
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
            'files' => 'nullable|array',
            'files.*' => 'nullable|url',
            'editable_files' => 'nullable|array',
            'editable_files.*' => 'nullable|url',
        ]);
    
        // ファイル情報を処理（名前を自動取得）
        $viewOnlyFiles = [];
        foreach ($validated['files'] ?? [] as $fileUrl) {
            $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
            $viewOnlyFiles[] = ['name' => $fileName, 'url' => $fileUrl];
        }
    
        $editableFiles = [];
        foreach ($validated['editable_files'] ?? [] as $fileUrl) {
            $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
            $editableFiles[] = ['name' => $fileName, 'url' => $fileUrl];
        }
    
        // 新しいマニュアルを作成
        Manual::create([
            'title' => $validated['title'],
            'content' => '', // 必要なら追加
            'hospital_id' => auth()->user()->hospital_id, // ユーザーの所属病院
            'department_id' => auth()->user()->department_id ?? null,
            'specialty_id' => null,
            'classification_id' => null,
            'procedure_id' => null,
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
            'editable_files.*' => 'nullable|url',
        ]);

        // 更新対象のマニュアルを取得
        $manual = Manual::findOrFail($manualId);

        // ファイル情報を処理（URLからファイル名を取得）
        $editableFiles = [];
        if (!empty($validated['editable_files'])) {
            foreach ($validated['editable_files'] as $fileUrl) {
                if (!empty($fileUrl)) { // 空の値を除外
                    $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
                    $editableFiles[] = ['name' => $fileName, 'url' => $fileUrl];
                }
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

}
