<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'OVERVIEW',
            'MANAGE USER',
            'MENU OPTION',
            'MANAGE ROLE',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ---------------------------------------------------
        // ADMIN SETUP
        // ---------------------------------------------------
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'role' => 'Admin', // Keeps the users table column synced
                'is_active' => true, 
            ]
        );

        $adminUser->assignRole($adminRole);

        // ---------------------------------------------------
        // STANDARD USER SETUP (Only has 'OVERVIEW')
        // ---------------------------------------------------
        $userRole = Role::firstOrCreate(['name' => 'User']);
        
        $userRole->givePermissionTo('OVERVIEW');

        $standardUser = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'User',
                'password' => Hash::make('user'), 
                'role' => 'User',
                'is_active' => true,
            ]
        );

        $standardUser->assignRole($userRole);
    }
}