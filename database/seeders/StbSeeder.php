<?php

namespace Database\Seeders;

use App\Models\Stb;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stb::create([
            'name' => 'TX MINI +',
            'ram' => 2,
            'internal' => 16
        ]);
    }
}
