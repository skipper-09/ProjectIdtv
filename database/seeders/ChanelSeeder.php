<?php

namespace Database\Seeders;

use App\Models\Chanel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chanel::create([
            'categori_id' => 1,
            'name' => 'INDOSIAR',
            'url' => 'http://op-group1-swiftservehd-1.dens.tv/h/h207/02.m3u8',
            'logo' => 'default.png',
            'type' => 'm3u',
        ]);
    }
}
