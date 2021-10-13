<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
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

    
        $user_role = Role::create(['name' => 'user']);         
        $admin_role = Role::create(['name' => 'admin']); 
        $super_admin = Role::create(['name' => 'super-admin']);
        
        
        Permission::create(['name' => 'my publish credit']);
        Permission::create(['name' => 'all publish credit']);

        $user_role->givePermissionTo('my publish credit');
        $admin_role->givePermissionTo('all publish credit');
        $super_admin->givePermissionTo(Permission::all());

    }
}
