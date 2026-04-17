<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing permissions and roles
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view-dashboard',
            
            // Penyakit permissions
            'read-penyakit',
            'create-penyakit',
            'update-penyakit',
            'delete-penyakit',
            
            // Laporan permissions
            'read-laporan',
            'create-laporan',
            'update-laporan',
            'delete-laporan',
            'export-laporan',
            'analisis-laporan',
            
            // User Management permissions
            'manage-users',
            'read-users',
            'create-users',
            'update-users',
            'delete-users',
            
            // Role Management permissions
            'manage-roles',
            'read-roles',
            'create-roles',
            'update-roles',
            'delete-roles',
            'assign-roles',
            
            // Permission Management permissions
            'manage-permissions',
            'read-permissions',
            'create-permissions',
            'update-permissions',
            'delete-permissions',
            'assign-permissions',
            
            // Permission Group Management permissions
            'manage-permission-groups',
            'read-permission-groups',
            'create-permission-groups',
            'update-permission-groups',
            'delete-permission-groups',
            
            // AI Assistant permissions
            'use-ai-assistant',
            'read-ai-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $operatorRole = Role::create(['name' => 'Operator']);

        // Assign all permissions to Super Admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to Admin
        $adminPermissions = [
            'view-dashboard',
            'read-penyakit',
            'create-penyakit',
            'update-penyakit',
            'delete-penyakit',
            'read-laporan',
            'create-laporan',
            'update-laporan',
            'delete-laporan',
            'export-laporan',
            'analisis-laporan',
            'read-users',
            'create-users',
            'update-users',
            'use-ai-assistant',
            'read-ai-dashboard',
        ];
        $adminRole->givePermissionTo($adminPermissions);

        // Assign limited permissions to Operator
        $operatorPermissions = [
            'view-dashboard',
            'read-penyakit',
            'read-laporan',
            'create-laporan',
            'update-laporan',
            'use-ai-assistant',
            'read-ai-dashboard',
        ];
        $operatorRole->givePermissionTo($operatorPermissions);

        $this->command->info('Permissions and roles created successfully!');
    }
}
