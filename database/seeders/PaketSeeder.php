<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
            'name' => 'Paket Minimum',
            'duration' => 1,
            'price' => '20000',
            'company_id'=>1,
        ]);
        Package::create([
            'name' => 'Paket Reseller',
            'duration' => 1,
            'price' => '10000',
            'company_id'=>1,
            'type_paket'=>'reseller'
        ]);
    }
}
