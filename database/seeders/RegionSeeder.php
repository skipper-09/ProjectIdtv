<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::insert(
            [
                [
                    'name' => 'BANYUWANGI',
                ],
                [
                    'name' => 'NDARU',
                ],
                [
                    'name' => 'GLAGAH',
                ],
                [
                    'name' => 'KERTOSARI',
                ],
                [
                    'name' => 'ROGOJAMPI',
                ],
                [
                    'name' => 'SRONO',
                ],
                [
                    'name' => 'JAJAG',
                ],
                [
                    'name' => 'JEMBER',
                ],
            ]

        );
    }
}
