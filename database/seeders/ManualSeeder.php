<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manual;

class ManualSeeder extends Seeder
{
    public function run(): void
    {
        Manual::create([
            'title'             => '胸腔鏡補助下肺切除術マニュアル',
            'content'           => 'マニュアルの詳細内容',
            'specialty_id'      => 1,
            'classification_id' => 1,
            'procedure_id'      => 1,
            'hospital_id'       => 1,
            'department_id'     => 1,
            'version'           => 1,
            'created_by'        => 1, // 存在するユーザーID（テスト用に、Breeze で作成されたユーザーがあればそのIDを使う）
            'updated_by'        => 1,
        ]);
    }
}
