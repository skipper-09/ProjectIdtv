<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Developer',
            'username' => 'developer',
            'email' => 'csnetdev@gmail.com',
            'password' => Hash::make('csnetdev'),
        ])->assignRole('Developer');
        User::create([
            'name' => 'Toko Agung',
            'username' => 'idvision',
            'email' => 'admin@idvision.co.id',
            'password' => Hash::make('password'),
        ])->assignRole('Owner');

        User::create([
            'name' => 'Staff',
            'username' => 'Staf',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('Staff');
        User::create([
            'name' => 'reseler',
            'username' => 'reseler',
            'email' => 'reseler@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('Reseller');
        User::create([
            'name' => 'Acounting',
            'username' => 'acounting',
            'email' => 'acounting@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('Acounting');
    }
}
