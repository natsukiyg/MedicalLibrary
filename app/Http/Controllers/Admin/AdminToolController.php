<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminToolController extends Controller
{
    public function index()
    {
        return view('admin.tools'); // 例：管理者ツールのメイン画面を表示
    }
}
