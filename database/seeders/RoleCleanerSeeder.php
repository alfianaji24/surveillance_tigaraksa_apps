<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleCleanerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing roles
        Role::query()->delete();
        
        // Create clean roles
        $adminRole = Role::create(['name' => 'admin']);
        $operatorRole = Role::create(['name' => 'operator']);
        
        // Get all permissions
        $allPermissions = Permission::all();
        
        // Assign all permissions to admin
        $adminRole->syncPermissions($allPermissions);
        
        // Assign limited permissions to operator
        $operatorPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'read-penyakit',
            'read-laporan',
            'read-icd10',
            'read-cdi',
            'create-laporan',
            'update-laporan'
        ])->get();
        
        $operatorRole->syncPermissions($operatorPermissions);
        
        // Update first user to have admin role
        $user = User::first();
        if ($user) {
            $user->syncRoles([$adminRole]);
        }
        
        $this->command->info('✅ Roles cleaned and reset successfully!');
        $this->command->info('📋 Created roles: admin, operator');
        $this->command->info('👤 Admin users: ' . User::role('admin')->count());
        $this->command->info('👤 Operator users: ' . User::role('operator')->count());
    }
}
