<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Knowledge;

class AdminKnowledgeController extends Controller
{
    /**
     * ナレッジ管理画面（全ナレッジの一覧）を表示する
     */
    public function index()
    {
        // 例：全ナレッジを取得（必要に応じてフィルタリング）
        $knowledges = Knowledge::all();

        return view('admin.knowledges.index', compact('knowledges'));
    }
}
