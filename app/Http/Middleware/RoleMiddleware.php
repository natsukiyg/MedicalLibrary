<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();
        // 単純な配列マッピングでロールを定義
        $roleMapping = [
            'staff'       => 0,
            'team_member' => 1,
            'admin'       => 2,
            'operator'    => 3,
        ];
        $requiredRole = $roleMapping[$role] ?? null;

        // ユーザーが存在し、userHospital が存在し、role 属性（整数としてキャスト済み）が一致するかチェック
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}