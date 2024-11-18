<?php

namespace Database\Seeders;

use App\Models\owner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        owner::insert([
            'name' => 'Toko Agung',
            'phone' => '6282132272224',
            'email' => 'admin@idvision.co.id',
            'address' => 'tes',
            'username' => 'tokoagung',
            'showpassword' => 'tokoagung',
            'password' => Hash::make('tokoagung')
        ]);
    }
}
