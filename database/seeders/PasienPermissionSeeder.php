<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PasienPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for pasien management
        $permissions = [
            'read-pasien',
            'create-pasien',
            'update-pasien',
            'delete-pasien',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all pasien permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Assign read-pasien to operator role
        $operatorRole = Role::where('name', 'operator')->first();
        if ($operatorRole) {
            $operatorRole->givePermissionTo('read-pasien');
        }

        $this->command->info('✅ Pasien permissions created and assigned successfully!');
        $this->command->info('📋 Created permissions: ' . implode(', ', $permissions));
    }
}
