<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name' => 'YACUP SUGIARTO / SUTOMO YUWONO - RT5 RW2 KAMPUNG BARU JL JUANDA NO 61',
            'company_id' => 1,
            'region_id' => 1,
            'stb_id' => 1,
            'mac' => '064220908733',
            'ppoe' => 'csnetjjg@3510072601520002',
            'phone' => '0876544646',
            'address' => 'RT5 RW2 KAMPUNG BARU JL JUANDA NO 61',
            'username' => 'ahmad',
            'password' => Hash::make('ahmad'),
        ]);
    }
}
