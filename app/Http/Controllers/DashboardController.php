<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ダッシュボードのビューを返す
        return view('dashboard'); 
        // あるいは return view('dashboard.index'); 
        // (ファイルパスに合わせて修正)
    }
}
