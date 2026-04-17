<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create Super Admin role
$role = Role::create(['name' => 'Super Admin']);

// Create admin user
$user = User::create([
    'name' => 'Administrator',
    'username' => 'admin',
    'email' => 'admin@gmail.com',
    'password' => Hash::make('Nzlpngcq007'),
    'phone' => '08123456789'
]);

// Assign role to user
$user->assignRole('Super Admin');

echo "Admin user created successfully!\n";
echo "Username: admin\n";
echo "Email: admin@gmail.com\n";
echo "Password: Nzlpngcq007\n";
