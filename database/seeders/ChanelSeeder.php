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
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/INDOSIAR_Logo.png/750px-INDOSIAR_Logo.png',
            'type' => 'm3u',
        ]);
    }
}
