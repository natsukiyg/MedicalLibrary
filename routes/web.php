<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\FacilityDepartmentRoleController;

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
    // 1) 診療科(specialty)一覧 → 選択
    Route::get('/manuals/specialties', [ManualController::class, 'specialtyIndex'])
        ->name('manuals.specialty.index');
    // 2) 診療科(specialty)を選択した後 → 分類一覧
    Route::get('/manuals/specialties/{specialty}/classifications', [ManualController::class, 'classificationIndex'])
        ->name('manuals.classification.index');
    // 3) 分類を選択した後 → 術式一覧
    Route::get('/manuals/specialties/{specialty}/classifications/{classification}/procedures', [ManualController::class, 'procedureIndex'])
        ->name('manuals.procedure.index');
    // 4) 術式を選択した後 → マニュアル詳細画面
    Route::get('/manuals/{manual}', [ManualController::class, 'show'])
        ->name('manuals.show');
    // 5) マニュアル編集（チームメンバー or 管理者向け）
    Route::get('/manuals/{manual}/edit', [ManualController::class, 'edit'])
        ->name('manuals.edit');
    Route::patch('/manuals/{manual}', [ManualController::class, 'update'])
        ->name('manuals.update');
    // 6) マニュアル削除（チームメンバー or 管理者向け）
    Route::get('/manuals/{manual}/delete', [ManualController::class, 'confirmDelete'])
        ->name('manuals.delete.confirm');
    Route::delete('/manuals/{manual}', [ManualController::class, 'destroy'])
        ->name('manuals.destroy');
    // 7) マニュアル新規作成（チームメンバー or 管理者向け）
    Route::get('/manuals/create', [ManualController::class, 'create'])
        ->name('manuals.create');
    Route::post('/manuals', [ManualController::class, 'store'])
        ->name('manuals.store');

    // ------------------------------
    // ナレッジ関連（同様にリソースルートや段階的画面を用意）
    // ------------------------------
    Route::resource('knowledges', KnowledgeController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    
    //ナレッジ削除（チームメンバー or 管理者向け）
    Route::get('/knowledges/{knowledge}/delete', [KnowledgeController::class, 'confirmDelete'])
        ->name('knowledges.delete.confirm');
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

        // 登録ユーザー一覧
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        // 未承認ユーザーリスト
        Route::get('/users/pending', [AdminUserController::class, 'pending'])
            ->name('users.pending');

        // 削除済みユーザーリスト
        Route::get('/users/deleted', [AdminUserController::class, 'deleted'])
            ->name('users.deleted');

        // 他に施設・部署の登録・編集など
        // Route::get('/departments', [...]);
    });

// ------------------------------
// 運営者向け（全体管理）
// role:operator ミドルウェアで運営者判定
// ------------------------------
Route::middleware(['auth', 'verified', 'role:operator'])
    ->prefix('operator')
    ->as('operator.')
    ->group(function () {

        // 運営者ダッシュボード
        Route::get('/dashboard', [OperatorDashboardController::class, 'index'])
            ->name('dashboard');

        // 全施設のユーザー管理、操作ログなど
        // Route::get('/all-users', [...]);
    });

// 施設 / 部署 / 権限変更画面
Route::get('/facility-department-role', [FacilityDepartmentRoleController::class, 'edit'])
->name('facility-department-role.edit');

// フォーム送信処理
Route::post('/facility-department-role', [FacilityDepartmentRoleController::class, 'update'])
->name('facility-department-role.update');