<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@foo.com',
            'password' => bcrypt('sooper@min.com'),
        ]);

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'Read Home',
            'Edit Profile',

            // permissions
            'Manage Permissions',
            'Create Permissions',
            'Read Permissions',
            'Update Permissions',
            'Delete Permissions',
            
            // roles
            'Manage Roles',
            'Create Roles',
            'Read Roles',
            'Update Roles',
            'Delete Roles',
            
            // users
            'Manage Users',
            'Create Users',
            'Read Users',
            'Update Users',
            'Delete Users',
            'Give Permissions To User',

            // Menus
            'Manage Menus',
            'Create Menus',
            'Read Menus',
            'Update Menus',
            'Delete Menus',

            // Oauth Clients
            'Manage Oauth Clients',
            'Create Oauth Clients',
            'Read Oauth Clients',
            'Update Oauth Clients',
            'Delete Oauth Clients',

        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $superadmin_role = Role::create(['name' => 'Superadmin']);
        $superadmin_role->givePermissionTo($permissions);

        $superadmin->assignRole('Superadmin');
    }
}
