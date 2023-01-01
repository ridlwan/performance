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
            'supervisor',
            'staff',
            'executive',
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
            'doing-attendance',
            'manage-attendance',
            'view-dashboard',
            'view-report',
            'manage-report',
            'manage-account',
            'manage-profile',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->first()) {
                Permission::create([
                    'name' => $permission
                ]);
            }
        }

        Permission::whereNotIn('name', $permissions)->delete();

        if ($supervisorRole = Role::where('name', 'supervisor')->first()) {
            $supervisorPermissions = [
                'doing-attendance',
                'manage-attendance',
                'view-dashboard',
                'view-report',
                'manage-report',
                'manage-account',
                'manage-profile'
            ];

            $supervisorPermissionId = Permission::whereIn('name', $supervisorPermissions)->pluck('id');
    
            $supervisorRole->givePermissionTo($supervisorPermissionId);
        }

        if ($staffRole = Role::where('name', 'staff')->first()) {
            $staffPermissions = [
                'doing-attendance',
                'manage-profile'
            ];

            $staffPermissionId = Permission::whereIn('name', $staffPermissions)->pluck('id');
    
            $staffRole->givePermissionTo($staffPermissionId);
        }

        if ($executiveRole = Role::where('name', 'executive')->first()) {
            $executivePermissions = [
                'view-dashboard',
                'view-report',
            ];

            $executivePermissionId = Permission::whereIn('name', $executivePermissions)->pluck('id');
    
            $executiveRole->givePermissionTo($executivePermissionId);
        }








        $supervisor = User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@opencloud.com',
            'password' => bcrypt('password'),
        ]);

        $supervisor->assignRole('supervisor');

        $fatur = User::create([
            'name' => 'Fatur Ridlwan',
            'email' => 'fatur.ridlwan@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $fatur->assignRole('supervisor');

        $erlinda = User::create([
            'name' => 'Erlinda Safitri',
            'email' => 'erlinda.safitri@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $erlinda->assignRole('supervisor');








        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@opencloud.com',
            'password' => bcrypt('password'),
        ]);

        $staff->assignRole('staff');
        
        $galuh = User::create([
            'name' => 'Galuh Danutirto',
            'email' => 'galuh.danutirto@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $galuh->assignRole('staff');

        $rizky = User::create([
            'name' => 'Rizky Ibrahim',
            'email' => 'rizky.ibrahim@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $rizky->assignRole('staff');
        
        $okitora = User::create([
            'name' => 'Okitora Winnetou',
            'email' => 'okitora.winnetou@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $okitora->assignRole('staff');
        
        $farhan = User::create([
            'name' => 'Farhan Hadi',
            'email' => 'farhan.hadi@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $farhan->assignRole('staff');

        $surya = User::create([
            'name' => 'Surya Permana',
            'email' => 'surya.permana@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $surya->assignRole('staff');
        
        $vanadia = User::create([
            'name' => 'Vanadia Equila',
            'email' => 'vanadia.equila@opencloud.id',
            'password' => bcrypt('password'),
            'reported' => User::REPORTED_YES,
        ]);

        $vanadia->assignRole('staff');
        
        $putu = User::create([
            'name' => 'Putu Wijaya',
            'email' => 'putu.wijaya@opencloud.id',
            'password' => bcrypt('password'),
        ]);

        $putu->assignRole('staff');
        





        

        $executive = User::create([
            'name' => 'Executive',
            'email' => 'executive@opencloud.com',
            'password' => bcrypt('password'),
        ]);

        $executive->assignRole('executive');
    }
}