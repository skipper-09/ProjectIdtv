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
            'read-users', 'create-users', 'update-users', 'delete-users',
            'read-chanel', 'create-chanel', 'update-chanel', 'delete-chanel',
            'read-categori', 'create-categori', 'update-categori', 'delete-categori',
            'read-role', 'create-role', 'update-role', 'delete-role',
            'read-customer', 'create-customer', 'update-customer', 'delete-customer',
        ];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());
    }
}
