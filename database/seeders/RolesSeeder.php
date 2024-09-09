<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'User']);

    $Admin = User::firstOrCreate([

        'name' => 'Safaa',
        'email' => 'Safaa@gmail.com',
        'password' => Hash::make ('12345678'),
    ]);
    $Admin->assignRole('Admin');

    $Manager = User::firstOrCreate([

        'name' => 'Manager',
        'email' => 'Manager@gmail.com',
        'password' => Hash::make ('12345678'),
    ]);

    $Manager->assignRole('Manager');

    $User = User::firstOrCreate([

        'name' => 'User',
        'email' => 'User@gmail.com',
        'password' => Hash::make('12345678'),
    ]);
    $User->assignRole('User');
}
}
