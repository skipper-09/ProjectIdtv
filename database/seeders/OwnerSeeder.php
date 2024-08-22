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
            'name' => 'ahmad',
            'phone' => '123445',
            'email' => 'ahmad@gmail.com',
            'address' => 'tes',
            'username' => 'ahmad',
            'showpassword' => 'ahmad',
            'password' => Hash::make('ahmad')
        ]);
    }
}
