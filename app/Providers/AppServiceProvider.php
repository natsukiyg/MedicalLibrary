<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register()
    {

    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       /*  // ビューコンポーザーを使用して、ビューに変数を渡す
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $hospitalName = '〇〇病院';
            $departmentName = '〇〇';
            $user_role = '〇〇〇〇';
    
            if (\Illuminate\Support\Facades\Auth::check()) {
                // ユーザーがログインしている場合
                $user = \Illuminate\Support\Facedes\Auth::user();
                if ($user->hospital) {
                    $hospitalName = $user->hospital->name ?? '〇〇病院';
                }
                if ($user->department) {
                    $departmentName = $user->department->name ?? '〇〇';
                }
                // user_role があれば
                // 例えば $user->role などを取得
                // $user_role = $user->role ?? '〇〇〇〇';
            }
    
            $view->with('hospitalName', $hospitalName);
            $view->with('departmentName', $departmentName);
            $view->with('user_role', $user_role);
        }); */
    }
}