<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manual;

class AdminManualController extends Controller
{
    /**
     * マニュアル管理画面（登録済みマニュアルの一覧）を表示する
     */
    public function index()
    {
        // 例：全マニュアルを取得（必要に応じてフィルタリング）
        $manuals = Manual::all();

        return view('admin.manuals.index', compact('manuals'));
    }
}
