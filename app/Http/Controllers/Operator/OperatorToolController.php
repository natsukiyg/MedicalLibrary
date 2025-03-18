<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperatorToolController extends Controller
{
    public function index()
    {
        // 運営者ツールのメイン画面を返す
        return view('operator.dashboard'); // 必要に応じて適切なビューに変更
    }
}
