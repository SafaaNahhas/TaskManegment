<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $permissions = [
        //                 'create tasks',
        //                 'edit tasks',
        //                 'delete tasks',
        //                 'assign tasks',
        //                 'view tasks',
        //                 'update status tasks',

        //                 'view users',
        //                 'create users',
        //                 'edit users',
        //                 'delete users',
        //                 'view own profile',

        // ];

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }


        // $admin = Role::findByName('Admin');
        // $admin->givePermissionTo(Permission::all());

        // $manager = Role::findByName('Manager');
        // $manager->givePermissionTo([
        //     'view tasks',
        //     'create tasks',
        //     'assign tasks',
        //     'edit tasks',
        //     'delete tasks',
        //     'view users',
        // ]);

        // $user = Role::findByName('User');
        // $user->givePermissionTo([
        //     'view tasks',
        //     'update status tasks',
        //     'view own profile',
        // ]);



    }
}
