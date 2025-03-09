<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Hospital;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // まず Hospital を取得（IDが1のテスト病院を前提）
        $hospital = Hospital::find(1);
        if ($hospital) {
            Department::create([
                'name'        => '手術室',
                'hospital_id' => $hospital->id,
            ]);
        }
    }
}
