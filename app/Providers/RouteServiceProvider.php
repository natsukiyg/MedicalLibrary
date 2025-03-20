<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * この名前空間は、ルートファイルのコントローラーで適用される
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * ルートの登録を行う
     */
    public function boot()
    {
        dd('RouteServiceProvider is being executed!');

        $this->routes(function() {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * アプリケーションのルートを定義する
     */
/*     public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    } */

    /**
     * API用のルートを定義
     */
/*     protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
 */
    /**
     * Web用のルートを定義
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
