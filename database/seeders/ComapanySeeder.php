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
                'user_id' => 2,
                'email'=> 'admin@idvision.co.id'
            ]
        );
    }
}
