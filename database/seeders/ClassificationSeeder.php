<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classification;
use App\Models\Specialty;

class ClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $specialty = Specialty::first();
        if ($specialty) {
            Classification::create([
                'specialty_id' => $specialty->id,
                'name'         => '胸腔鏡下手術',
            ]);
        }
    }
}
