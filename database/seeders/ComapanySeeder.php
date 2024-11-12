<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComapanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(
            [
                    'name' => 'IDVISION',
                    'address' => 'Jl Soekarno Hatta(Merr), Ruko este square Kec. Mulyarejo Surabaya',
                    'phone' => '6282132272224',
                    'user_id' => 3,
                    'fee_reseller' => 0,
                    'rekening' => 23232323232323,
                    'bank_name' => 'BCA',
                    'owner_rek' => 'IDVISION',
            ]
        );
    }
}
