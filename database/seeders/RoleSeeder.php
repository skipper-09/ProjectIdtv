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
        $staff = Role::create(['name'=>'Staff']);
        //give permission role
        $developer->givePermissionTo([
            'read-dashboard',
            'read-users', 'create-users', 'update-users', 'delete-users',
            'read-chanel', 'create-chanel', 'update-chanel', 'delete-chanel',
            'read-categori', 'create-categori', 'update-categori', 'delete-categori',
            'read-owner', 'create-owner', 'update-owner', 'delete-owner',
            'read-role', 'create-role', 'update-role', 'delete-role',
            'read-customer', 'create-customer', 'update-customer', 'delete-customer',

        ]);

        $staff->givePermissionTo([
            'read-dashboard',
            'read-users', 'create-users', 'update-users', 'delete-users',
            'read-chanel', 
            'read-categori',
            'read-role',
        ]);
    }
}
