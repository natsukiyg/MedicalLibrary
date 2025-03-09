<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hospital::create([
            'name'    => 'テスト病院',
            'address' => '東京都テスト区テスト町 1-2-3',
        ]);
    }
}