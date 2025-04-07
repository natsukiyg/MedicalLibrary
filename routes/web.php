<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\FacilityDepartmentRoleController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminToolController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminManualController;
use App\Http\Controllers\Admin\AdminKnowledgeController;
use App\Http\Controllers\Operator\OperatorToolController;
use App\Services\GraphService;
use App\Http\Controllers\ManualAnalysisController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// デバッグ用
/* Route::get('/debug-middleware', function () {
    dd(app('router')->getMiddleware());
}); */
Route::get('/debug-middleware', function () {
    return response()->json(app('router')->getMiddleware());
});
/* Route::get('/debug-middleware', function () {
    return response()->json([
        'role' => app('router')->getMiddleware()['role'] ?? 'not found',
    ]);
}); */

// ------------------------------
// トップページ（公開）
// ------------------------------
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ------------------------------
// 認証関連（Breeze / auth.php）
// ------------------------------
require __DIR__.'/auth.php';

// ------------------------------
// ユーザー向け（スタッフ/チームメンバーなど）
// 認証必須: auth, verified
// ------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // --- ダッシュボード ---
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // --- プロフィール関連 ---
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ------------------------------
    // マニュアル関連（診療科 → 種類 → 術式 → マニュアル詳細）
    // ------------------------------
    Route::get('/manuals/create', [ManualController::class, 'create'])
        ->name('manuals.create');    
    Route::post('/manuals', [ManualController::class, 'store'])
        ->name('manuals.store');
    Route::get('/manuals', [ManualController::class, 'specialtyIndex'])
        ->name('manuals.index');
    Route::get('/manuals/specialties', [ManualController::class, 'specialtyIndex'])
        ->name('manuals.specialty.index');
    Route::get('/manuals/specialties/{specialty}/classifications', [ManualController::class, 'classificationIndex'])
        ->name('manuals.classification.index');
    Route::get('/manuals/specialties/{specialty}/classifications/{classification}/procedures', [ManualController::class, 'procedureIndex'])
        ->name('manuals.procedure.index');
    // マニュアルの検索（固定パス）
    Route::get('/manuals/search', [ManualController::class, 'search'])
        ->name('manuals.search');
    // マニュアルの詳細表示
    Route::get('/manuals/{manual}', [ManualController::class, 'show'])
        ->name('manuals.show');
    Route::get('/manuals/{manual}/edit', [ManualController::class, 'edit'])
        ->name('manuals.edit');
    Route::PUT('/manuals/{manual}', [ManualController::class, 'update'])
        ->name('manuals.update');
    Route::get('/manuals/{manual}/delete', [ManualController::class, 'confirmDelete'])
        ->name('manuals.delete.confirm');
    Route::delete('/manuals/{manual}', [ManualController::class, 'destroy'])
        ->name('manuals.destroy');
    Route::get('/manuals/title-by-procedure/{procedure}', [ManualController::class, 'getTitleByProcedure']);

    // ------------------------------
    // ナレッジ関連（同様にリソースルートや段階的画面を用意）
    // ------------------------------
    Route::resource('knowledges', KnowledgeController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/knowledges/{knowledge}/delete', [KnowledgeController::class, 'confirmDelete'])
        ->name('knowledges.delete.confirm');

    // ------------------------------
    // 施設 / 部署 / 権限変更画面
    // ※ ユーザー向けなので auth, verified で保護
    // ------------------------------
    Route::get('/facility-department-role', [FacilityDepartmentRoleController::class, 'edit'])
         ->name('facility-department-role.edit');
    Route::post('/facility-department-role', [FacilityDepartmentRoleController::class, 'update'])
         ->name('facility-department-role.update');
});

// ------------------------------
// 管理者向け（施設/部署の管理者）
// role:admin ミドルウェアで管理者判定
// ------------------------------
/* Route::middleware(['auth', 'verified', function($request, $next) {
    return (new \App\Http\Middleware\RoleMiddleware)->handle($request, $next, 'admin');
}]) */
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        // 管理者ダッシュボード
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
        
        // 管理者ツール
        Route::get('/tools', [AdminToolController::class, 'index'])
            ->name('tools');

        // 登録ユーザー一覧
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        // 未承認ユーザーリスト
        Route::get('/users/pending', [AdminUserController::class, 'pending'])
            ->name('users.pending');

        // 削除済みユーザーリスト
        Route::get('/users/deleted', [AdminUserController::class, 'deleted'])
            ->name('users.deleted');

        // マニュアル管理
        Route::get('/manuals', [AdminManualController::class, 'index'])
            ->name('manuals.index');
        
        // ナレッジシェア管理
        Route::get('/knowledges', [AdminKnowledgeController::class, 'index'])
            ->name('knowledges.index');
});

Route::middleware(['web'])->group(function() {
    // 診療科ごとの分類を取得するAPI
    Route::get('/classifications/{specialty}', [ManualController::class, 'getClassifications']);

    // 分類ごとの術式を取得するAPI
    Route::get('/procedures/{classification}', [ManualController::class, 'getProcedures']);
});

// ------------------------------
// 将来的なGraph API導入用テストコード（コメントアウトのまま残す）
// ------------------------------
/* Route::get('/test-msgraph-meta', function () {
    $shareUrl = 'https://1drv.ms/x/c/e9063c3cba030461/EWdHl2zj9H1NieCyE3Xh7tMBL0EH075DCF124Gp4QlIL0Q';

    $accessToken = \App\Services\GraphService::getAccessToken();

    $encodedUrl = rtrim(strtr(base64_encode($shareUrl), '+/', '-_'), '=');

    $response = Http::withToken($accessToken)
        ->get("https://graph.microsoft.com/v1.0/shares/u!{$encodedUrl}");

    return $response->json();
}); */

/* Route::get('/test-msgraph', function () {
    $shareUrl = 'https://onedrive.live.com/edit?id=E9063C3CBA030461!s6c974767f4e34d7d89e0b21375e1eed3&resid=E9063C3CBA030461!s6c974767f4e34d7d89e0b21375e1eed3&cid=e9063c3cba030461&ithint=file%2Cxlsx&redeem=aHR0cHM6Ly8xZHJ2Lm1zL3gvYy9lOTA2M2MzY2JhMDMwNDYxL0VXZEhsMnpqOUgxTmllQ3lFM1hoN3RNQkwwRUgwNzVEQ0YxMjRHcDRRbElMMFE&migratedtospo=true&wdo=2'; // ← ここにOneDriveの共有リンクを入れる

    $content = GraphService::getFileContentFromShareUrl($shareUrl);

    return response($content)->header('Content-Type', 'text/plain');
}); */

// マニュアル読み込みのテスト用ルートを追加
Route::get('/manuals/{manual}/preview', [ManualController::class, 'previewText']);

// マニュアル分析のルート
Route::post('/manuals/{manual}/analyze', [ManualAnalysisController::class, 'analyze'])
    ->name('manuals.analyze');

// マニュアル分析に対する履歴保存のためのルート
Route::post('/manuals/{manual}/analyze/followup', [ManualAnalysisController::class, 'analyzeFollowup'])
    ->name('manuals.analyze.followup');

// AI分析履歴削除のルート
Route::delete('/ai-analyses/{analysis}', [ManualAnalysisController::class, 'destroy'])
    ->name('ai-analyses.destroy');

//確認用
Route::get('/check-laravel', function () {
    return 'Laravel is working correctly!';
});