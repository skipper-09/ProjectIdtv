<?php

namespace Database\Seeders;

use App\Models\Categori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categori::create([
            'name' => 'NASIONAL',
            'created_at' => Carbon::now()
        ]);
    }
}
