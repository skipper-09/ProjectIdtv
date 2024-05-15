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

        $developer->givePermissionTo([
            'read-dashboard',
            'read-users', 'create-users', 'update-users', 'delete-users',
        ]);
    }
}
