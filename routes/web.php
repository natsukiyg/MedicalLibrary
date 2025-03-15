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
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\OperatorToolController;
use App\Http\Controllers\Operator\OperatorUserController;
use App\Http\Controllers\Operator\OperatorManualController;
use App\Http\Controllers\Operator\OperatorKnowledgeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
    Route::get('/manuals/specialties', [ManualController::class, 'specialtyIndex'])
        ->name('manuals.specialty.index');
    Route::get('/manuals/specialties/{specialty}/classifications', [ManualController::class, 'classificationIndex'])
        ->name('manuals.classification.index');
    Route::get('/manuals/specialties/{specialty}/classifications/{classification}/procedures', [ManualController::class, 'procedureIndex'])
        ->name('manuals.procedure.index');
    Route::get('/manuals/{manual}', [ManualController::class, 'show'])
        ->name('manuals.show');
    Route::get('/manuals/{manual}/edit', [ManualController::class, 'edit'])
        ->name('manuals.edit');
    Route::patch('/manuals/{manual}', [ManualController::class, 'update'])
        ->name('manuals.update');
    Route::get('/manuals/{manual}/delete', [ManualController::class, 'confirmDelete'])
        ->name('manuals.delete.confirm');
    Route::delete('/manuals/{manual}', [ManualController::class, 'destroy'])
        ->name('manuals.destroy');
    Route::get('/manuals/create', [ManualController::class, 'create'])
        ->name('manuals.create');
    Route::post('/manuals', [ManualController::class, 'store'])
        ->name('manuals.store');

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

// ------------------------------
// 運営者向け（全体管理）
// role:operator ミドルウェアで運営者判定
// ------------------------------
Route::middleware(['auth', 'verified', 'role:operator'])
    ->prefix('operator')
    ->as('operator.')
    ->group(function () {

        // 運営者ツール
        Route::get('/dashboard', [OperatorToolController::class, 'index'])
            ->name('dashboard');

        // 全施設のユーザー管理、操作ログなど
        // Route::get('/all-users', [...]);
    });
