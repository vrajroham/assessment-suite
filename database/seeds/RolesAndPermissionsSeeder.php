<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        // Permission::create(['name' => 'edit posts']);
        // Permission::create(['name' => 'delete posts']);
        // Permission::create(['name' => 'delete users']);

        // create roles and assign existing permissions
        $role = Role::create(['name' => 'superadmin']);
        // $role->givePermissionTo('edit posts');
        // $role->givePermissionTo('delete posts');

        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo('delete users');
        $role = Role::create(['name' => 'principal']);
        $role = Role::create(['name' => 'hod']);
        $role = Role::create(['name' => 'teacher']);
        $role = Role::create(['name' => 'candidate']);
    }
}
