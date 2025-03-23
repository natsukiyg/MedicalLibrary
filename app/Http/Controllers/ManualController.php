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
            'specialty_id' => 'required|exists:specialties,id',
            'classification_id' => 'required|exists:classifications,id',
            'procedure_id' => 'required|exists:procedures,id',
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
        $userHospital = auth()->user()->userHospital;
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

}
