<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfPermissionNames = [
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
            'read-role',
            'create-role',
            'update-role',
            'delete-role',
            'read-customer',
            'create-customer',
            'update-customer',
            'delete-customer',
            'renew-customer',
            'reset-device',
            'read-company',
            'create-company',
            'update-company',
            'delete-company',
            'read-owner',
            'create-owner',
            'update-owner',
            'delete-owner',
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
            'read-log',
            'clean-log',
            'read-tagihan',
            'read-curentstream',
            'read-version_control',
            'create-version_control',
            'update-version_control',
            'delete-version_control',
            'read-genre',
            'create-genre',
            'update-genre',
            'delete-genre',
            'read-movie',
            'create-movie',
            'update-movie',
            'delete-movie',
            'read-movie-player',
            'read-episode',
            'create-episode',
            'update-episode',
            'delete-episode',
            'read-episode-player'
        ];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());
    }
}
