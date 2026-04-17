<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperadminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get superadmin role
        $superadminRole = Role::where('name', 'Superadmin')->first();
        
        if (!$superadminRole) {
            $this->command->error('Superadmin role not found!');
            return;
        }

        // Get all permissions
        $allPermissions = Permission::all();
        
        // Assign all permissions to superadmin
        $superadminRole->syncPermissions($allPermissions);

        $this->command->info('✅ All permissions assigned to superadmin successfully!');
        $this->command->info('📋 Total permissions assigned: ' . $allPermissions->count());
    }
}
