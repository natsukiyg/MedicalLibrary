<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();
        // ユーザーのロールに応じてアクセス権限を設定
        $roleMapping = [
            'staff'       => 0,
            'team_member' => 1,
            'admin'       => 2,
            'operator'    => 3,
        ];
        $requiredRole = $roleMapping[$role] ?? null;
        // ユーザーが存在し、userHospitalが存在し、roleが一致するかチェック
        if ($user && $user->userHospital && $user->userHospital->role == $requiredRole) {
            return $next($request);
        }
        abort(403, 'このページにアクセスする権限がありません。');
    }
}
