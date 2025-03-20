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
            'version'           => 1.0,
            'created_by'        => 1, // 存在するユーザーID（テスト用に、Breeze で作成されたユーザーがあればそのIDを使う）
            'updated_by'        => 1,
            //'files'（閲覧専用）をファイル名付きの形式で保存
            'files' => json_encode([
                [
                    'name' => '呼吸器外科VATSマニュアルサンプル.xlsx',
                    'url'  => 'https://1drv.ms/x/c/e9063c3cba030461/EWdHl2zj9H1NieCyE3Xh7tMBL0EH075DCF124Gp4QlIL0Q?e=8e7L2P'
                ]
            ]), 
            // 'editable_files'（編集可能）をファイル名付きの形式で保存
            'editable_files' => json_encode([
                [
                     'name' => '呼吸器外科VATSマニュアルサンプル.xlsx',
                     'url'  => 'https://1drv.ms/x/c/e9063c3cba030461/EWdHl2zj9H1NieCyE3Xh7tMBVPlaS3uGfNn-ihtLFuls7A?e=ropgK9'
                ]
            ]),
        ]);
    }
}
