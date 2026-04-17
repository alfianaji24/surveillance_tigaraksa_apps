<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get the Superadmin role
        $superadminRole = Role::firstOrCreate(['name' => 'Superadmin']);
        
        // Get all permissions
        $permissions = Permission::all();
        
        // Assign all permissions to Superadmin role
        $superadminRole->syncPermissions($permissions);
        
        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@sipoli.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]
        );
        
        // Assign Superadmin role to user
        $user->syncRoles([$superadminRole]);
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@sipoli.com');
        $this->command->info('Password: password');
        $this->command->info('Role: Superadmin (All permissions)');
    }
}
