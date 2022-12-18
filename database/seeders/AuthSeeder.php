<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $roles = [
            'admin',
        ];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->first()) {
                Role::create([
                    'name' => $role
                ]);
            }
        }

        Role::whereNotIn('name', $roles)->delete();

        $permissions = [
            'view-dashboard',
            'view-documents',
            'create-documents',
            'update-documents',
            'delete-documents',
            'receive-documents',
            'view-users',
            'create-users',
            'update-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'update-roles',
            'delete-roles',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->first()) {
                Permission::create([
                    'name' => $permission
                ]);
            }
        }

        Permission::whereNotIn('name', $permissions)->delete();

        if ($adminRole = Role::where('name', 'admin')->first()) {
            $adminPermissions = [
                'view-dashboard',
                'view-documents',
                'create-documents',
                'update-documents',
                'delete-documents',
                'receive-documents',
                'view-users',
                'create-users',
                'update-users',
                'delete-users',
                'view-roles',
                'create-roles',
                'update-roles',
                'delete-roles',
            ];

            $adminPermissionId = Permission::whereIn('name', $adminPermissions)->pluck('id');
    
            $adminRole->givePermissionTo($adminPermissionId);
        }

        if (!User::where('email', 'admin@tes.com')->first()) {
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@tes.com',
                'password' => bcrypt('password'),
            ]);
    
            $admin->assignRole('admin');
        }
    }
}
