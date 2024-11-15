<?php

namespace Database\Seeders;

use App\Models\Reseller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reseller::create([
            'company_id' => 1,
            'user_id' => 4,
            'bank_id' => 1,
            'name' => 'PT Cahaya Solusindo',
            'address' => 'Banyuwangi',
            'phone' => '082323343545',
            'rekening' => '234234234234',
            'owner_rek' => 'CSNET'
        ]);
    }
}
