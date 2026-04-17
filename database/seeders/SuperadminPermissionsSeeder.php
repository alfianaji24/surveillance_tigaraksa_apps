<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperadminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create superadmin role
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Get all permissions
        $permissions = Permission::all();
        
        // Assign all permissions to superadmin role
        $superadminRole->syncPermissions($permissions);
        
        // Find superadmin user and assign role
        $superadmin = User::where('email', 'superadmin@example.com')->first();
        if ($superadmin) {
            $superadmin->assignRole('superadmin');
        }
        
        $this->command->info('Superadmin permissions updated successfully!');
    }
}
