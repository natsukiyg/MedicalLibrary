<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualController;

Route::middleware('api')->group(function() {
    // 診療科ごとの分類を取得するAPI
    Route::get('/classifications/{specialtyId}', [ManualController::class, 'getClassifications']);

    // 分類ごとの術式を取得するAPI
    Route::get('/procedures/{classificationId}', [ManualController::class, 'getProcedures']);
});

