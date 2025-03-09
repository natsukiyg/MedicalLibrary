<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;
use App\Models\Department;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        // 先ほど作成した Department (ID=1) を取得
        $department = Department::find(1);
        if ($department) {
            Specialty::create([
                'department_id' => $department->id,
                'name'          => '呼吸器外科',
            ]);
        }
    }
}
