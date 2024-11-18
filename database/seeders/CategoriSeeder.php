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
        Categori::insert([
        [
            'name' => 'NASIONAL',
        ],
        [
            'name' => 'MOVIE',
        ],
        [
            'name' => 'SPORTS',
        ],
        [
            'name' => 'KIDS',
        ],
        [
            'name' => 'NEWS',
        ],
        [
            'name' => 'MUSIC',
        ],
        [
            'name' => 'NATURE',
        ],
        
    ]);
    }
}
