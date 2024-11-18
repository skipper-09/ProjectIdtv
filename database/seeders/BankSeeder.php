<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'name'=>'BCA'
        ]);
        Bank::create([
            'name'=>'BNI'
        ]);
        Bank::create([
            'name'=>'BRI'
        ]);
        Bank::create([
            'name'=>'MANDIRI'
        ]);
    }
}
