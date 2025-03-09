<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 依存関係のある順番にシーダーを呼び出す
        $this->call([
            HospitalSeeder::class,
            DepartmentSeeder::class,
            SpecialtySeeder::class,
            ClassificationSeeder::class,
            ProcedureSeeder::class,
            ManualSeeder::class,
        ]);
    }
}
