<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RekapDiagnosaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permission for rekap diagnosa
        Permission::firstOrCreate(['name' => 'read-rekap-diagnosa']);

        // Assign permission to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo('read-rekap-diagnosa');
        }

        // Assign permission to operator role
        $operatorRole = Role::where('name', 'operator')->first();
        if ($operatorRole) {
            $operatorRole->givePermissionTo('read-rekap-diagnosa');
        }

        $this->command->info('✅ Rekap Diagnosa permission created and assigned successfully!');
    }
}
