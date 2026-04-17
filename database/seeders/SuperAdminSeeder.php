<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create superadmin role
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Define all permissions
        $permissions = [
            // User Management
            'read-user',
            'create-user', 
            'update-user',
            'delete-user',
            'manage-roles',
            'manage-permissions',
            
            // Penyakit Management
            'read-penyakit',
            'create-penyakit',
            'update-penyakit', 
            'delete-penyakit',
            'import-penyakit',
            'export-penyakit',
            
            // Laporan Management
            'read-laporan',
            'create-laporan',
            'update-laporan',
            'delete-laporan',
            'analisis-laporan',
            'export-laporan',
            
            // ICD-10 Management
            'read-icd10',
            'create-icd10',
            'update-icd10',
            'delete-icd10',
            
            // CDI Management
            'read-cdi',
            'create-cdi',
            'update-cdi',
            'delete-cdi',
        ];
        
        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Give all permissions to superadmin role
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);
        
        // Ensure the first user has superadmin role
        $user = User::first();
        if ($user && !$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }
        
        $this->command->info(' SuperAdmin role and permissions setup completed!');
        $this->command->info(' Total permissions: ' . $allPermissions->count());
        $this->command->info(' SuperAdmin users: ' . User::role('superadmin')->count());
    }
}
