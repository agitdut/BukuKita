<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Permission
        Permission::firstOrCreate(['name' => 'view books']);
        Permission::firstOrCreate(['name' => 'create books']);
        Permission::firstOrCreate(['name' => 'edit books']);
        Permission::firstOrCreate(['name' => 'delete books']);
        Permission::firstOrCreate(['name' => 'view loans']);
        Permission::firstOrCreate(['name' => 'create loans']);
        Permission::firstOrCreate(['name' => 'manage users']);

        // 2. Buat Role dan beri Permission
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view books',
            'create books',
            'edit books',
            'view loans',
            'create loans'
        ]);
        
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $memberRole->givePermissionTo(['view books']);


        // 3. Buat User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $admin->assignRole($adminRole);

        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            ['name' => 'Staff User', 'password' => Hash::make('password')]
        );
        $staff->assignRole($staffRole);

        $member = User::firstOrCreate(
            ['email' => 'member@gmail.com'],
            ['name' => 'Member User', 'password' => Hash::make('password')]
        );
        $member->assignRole($memberRole);

    }
}