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
        //バリデーションルールを定義
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'specialty_id' => 'required|exists:specialties,id',
            'classification_id' => 'required|exists:classifications,id',
            'procedure_id' => 'required|exists:procedures,id',
        ]);

        //マニュアルを新規作成
        $manual = new Manual();
        $manual->title = $request->title;
        $manual->content = $request->content;
        $manual->specialty_id = $request->specialty_id;
        $manual->classification_id = $request->classification_id;
        $manual->procedure_id = $request->procedure_id;
        $manual->created_by = $request->user()->id;
        $manual->updated_by = $request->user()->id;
        $manual->save();
        //マニュアルの詳細画面にリダイレクト
        return redirect()->route('manuals.show', $manual->id)
                         ->with('success', 'マニュアルを作成しました');
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
        //バリデーションルールを定義
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'specialty_id' => 'required|exists:specialties,id',
            'classification_id' => 'required|exists:classifications,id',
            'procedure_id' => 'required|exists:procedures,id',
        ]);

        //更新対象のマニュアルを取得
        $manual = Manual::findOrFail($manualId);
        $manual->title = $request->title;
        $manual->content = $request->content;
        $manual->specialty_id = $request->specialty_id;
        $manual->classification_id = $request->classification_id;
        $manual->procedure_id = $request->procedure_id;
        $manual->updated_by = $request->user()->id;
        $manual->save();

        $manual->update($data);

        //マニュアルの詳細画面にリダイレクト
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
}
