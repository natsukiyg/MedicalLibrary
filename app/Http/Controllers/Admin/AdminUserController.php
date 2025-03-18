<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // 必要に応じて

class AdminUserController extends Controller
{
    // 登録ユーザー一覧
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // 未承認ユーザーリスト
    public function pending()
    {
        $users = User::where('approval_status', 0)->get();
        return view('admin.users.pending', compact('users'));
    }

    // 削除済みユーザーリスト（ソフトデリートの場合）
    public function deleted()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.users.deleted', compact('users'));
    }
}
