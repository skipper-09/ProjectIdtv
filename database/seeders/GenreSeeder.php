<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::insert([
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Animated'],
            ['name' => 'Biography'],
            ['name' => 'Comedy'],
            ['name' => 'Crime'],
            ['name' => 'Dance'],
            ['name' => 'Disaster'],
            ['name' => 'Documentary'],
            ['name' => 'Drama'],
            ['name' => 'Erotic'],
            ['name' => 'Family'],
            ['name' => 'Fantasy'],
            ['name' => 'Horror'],
        ]);
    }
}
