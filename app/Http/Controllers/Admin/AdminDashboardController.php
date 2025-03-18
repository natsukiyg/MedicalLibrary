<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    // 管理者向けダッシュボード
    public function index()
    {
        // ダッシュボードのビューを返す
        return view('admin.dashboard');
    }
}
