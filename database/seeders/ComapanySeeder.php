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
        Company::create([
            'name' => 'PT. CAHAYA SOLUSINDO',
            'address' => 'Jl. Letjen S Parman No.58, Sumberrejo, Pakis, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68419',
            'phone' => '6282111777179',
        ]);
    }
}
