<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixUserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('No users found in database!');
            return;
        }
        
        // Create or get the Superadmin role
        $superadminRole = Role::firstOrCreate(['name' => 'Superadmin']);
        
        // Get manage-poli permission
        $managePoliPermission = Permission::firstOrCreate(['name' => 'manage-poli']);
        
        // Assign all permissions to Superadmin role
        $allPermissions = Permission::all();
        $superadminRole->syncPermissions($allPermissions);
        
        // Assign Superadmin role to first user
        $firstUser = $users->first();
        $firstUser->syncRoles([$superadminRole]);
        
        $this->command->info('User permissions fixed successfully!');
        $this->command->info('User: ' . $firstUser->name . ' (' . $firstUser->email . ')');
        $this->command->info('Role: Superadmin (All permissions including manage-poli)');
        
        // Show user permissions
        $this->command->info('User has manage-poli permission: ' . ($firstUser->hasPermissionTo('manage-poli') ? 'YES' : 'NO'));
    }
}
