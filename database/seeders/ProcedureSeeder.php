<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Procedure;
use App\Models\Classification;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $classification = \App\Models\Classification::first();
        if ($classification) {
            Procedure::create([
                'classification_id' => $classification->id,
                'name'              => '胸腔鏡補助下肺切除術',
            ]);
        }
    }
}
