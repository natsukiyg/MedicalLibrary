<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityDepartmentRoleController extends Controller
{
    /**
     * 施設 / 部署 / 権限変更フォームの表示
     */
    public function edit()
    {
        return view('facility_department_role.edit');
    }

    /**
     * フォームからの更新リクエストを受け取り、処理する
     */
    public function update(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'hospital'   => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role'       => 'required|in:staff,team_member,admin',
        ]);

        // ログインユーザーのuser_hospital情報を取得
        $user = Auth::user();
        $userHospital = $user->userHospital; // hasOne リレーション

        // 役割文字列を整数に変換
        $roleValue = $this->mapRole($validated['role']);

        if ($userHospital) {
            // 既存データがあれば更新
            $userHospital->update([
                'hospital_id'   => $this->resolveHospitalId($validated['hospital']),
                'department_id' => $this->resolveDepartmentId($validated['department']),
                'role'          => $roleValue,
            ]);
        } else {
            // 既存データがなければ新規作成
            \App\Models\UserHospital::create([
                'user_id'       => $user->id,
                'hospital_id'   => $this->resolveHospitalId($validated['hospital']),
                'department_id' => $this->resolveDepartmentId($validated['department']),
                'role'          => $roleValue,
            ]);
        }

        // 処理後のリダイレクト先
        return redirect()->route('facility-department-role.edit')
                         ->with('success', '更新しました。');
    }

    /**
     * 役割の文字列を整数にマッピングする
     * 'staff'       => 0,
     * 'team_member' => 1,
     * 'admin'       => 2,
     */
    protected function mapRole(string $role): ?int
    {
        $mapping = [
            'staff'       => 0,
            'team_member' => 1,
            'admin'       => 2,
        ];

        return $mapping[$role] ?? null;
    }

    // 入力された施設名から適切なhospital_idを取得する処理
    protected function resolveHospitalId(string $hospitalName)
    {
        $hospital = \App\Models\Hospital::where('name', $hospitalName)->first();
        return $hospital ? $hospital->id : null;
    }

    // 入力された部署名から適切なdepartment_idを取得する処理
    protected function resolveDepartmentId(string $departmentName)
    {
        $department = \App\Models\Department::where('name', $departmentName)->first();
        return $department ? $department->id : null;
    }
}
