<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermission extends Seeder
{
    /**
     * Run the permissions and roles seeds.
     *
     * @return void
     */
    public function run()
    {
        $create_user = Permission::create(['name' => 'create_user']);
        $read_user   = Permission::create(['name' => 'read_user']);
        $update_user = Permission::create(['name' => 'update_user']);
        $delete_user = Permission::create(['name' => 'delete_user']);

        $super = Role::create(['name' => 'super']);
        $admin = Role::create(['name' => 'admin']);

        $super->givePermissionTo([
            $create_user,
            $read_user,
            $update_user,
            $delete_user
        ]);

        $admin->givePermissionTo([
            $create_user,
            $read_user,
            $update_user
        ]);
    }
}
