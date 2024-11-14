<?php

namespace Database\Seeders;

use App\Models\ResellerPaket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResellerPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResellerPaket::create([
            'paket_id'=>2,
            'reseller_id'=>1,
            'name'=>'Paket Reseller 1',
            'price'=>'20000',
            'total'=>'30000'
        ]);
    }
}
