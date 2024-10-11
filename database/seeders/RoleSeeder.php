<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = Role::create(['name' => 'Developer']);
        $staff = Role::create(['name' => 'Staff']);
        $administrator = Role::create(['name' => 'Administrator']);
        $reseler = Role::create(['name' => 'Reseller']);
        //give permission role
        $developer->givePermissionTo([
            'read-dashboard',
            'read-users',
            'create-users',
            'update-users',
            'delete-users',
            'read-chanel',
            'create-chanel',
            'update-chanel',
            'delete-chanel',
            'read-chanel-player',
            'read-categori',
            'create-categori',
            'update-categori',
            'delete-categori',
            'read-owner',
            'create-owner',
            'update-owner',
            'delete-owner',
            'read-role',
            'create-role',
            'update-role',
            'delete-role',
            'read-customer',
            'create-customer',
            'update-customer',
            'delete-customer',
            'reset-device',
            'read-company',
            'create-company',
            'update-company',
            'delete-company',
            'read-region',
            'create-region',
            'update-region',
            'delete-region',
            'read-stb',
            'create-stb',
            'update-stb',
            'delete-stb',
            'read-paket',
            'create-paket',
            'update-paket',
            'delete-paket',
            'delete-log',
            'read-income-harian',
            'read-statistik',
            'read-feeclaim',
            'read-income-periode',
            'read-log','clean-log','read-tagihan'
        ]);

        $administrator->givePermissionTo([
            'read-dashboard',
            'read-users',
            'create-users',
            'update-users',
            'delete-users',
            'read-chanel',
            'create-chanel',
            'update-chanel',
            'delete-chanel',
            'read-chanel-player',
            'read-categori',
            'create-categori',
            'update-categori',
            'delete-categori',
            'read-owner',
            'create-owner',
            'update-owner',
            'delete-owner',
            'read-role',
            'create-role',
            'update-role',
            'delete-role',
            'read-customer',
            'create-customer',
            'update-customer',
            'delete-customer',
            'read-company',
            'create-company',
            'update-company',
            'delete-company',
            'read-region',
            'create-region',
            'update-region',
            'delete-region',
            'read-stb',
            'create-stb',
            'update-stb',
            'delete-stb',
            'read-paket',
            'create-paket',
            'update-paket',
            'delete-paket',
            'delete-log',
            'read-income-harian',
           'read-statistik',
            'read-feeclaim',
            'read-income-periode',
            'read-log','clean-log','read-tagihan'
        ]);

        $staff->givePermissionTo([
            'read-dashboard',
            'read-users',
            'create-users',
            'update-users',
            'delete-users',
            'read-customer'
        ]);

        $reseler->givePermissionTo([
            'read-dashboard',
            'read-customer',
        ]);
    }
}
