<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'list article', 'create article', 'edit article', 'delete article',
            'list category', 'create category', 'edit category', 'delete category',
            'list tag', 'create tag', 'edit tag', 'delete tag'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'admin' => [
                'list article', 'create article', 'edit article', 'delete article', 'list category', 'create category', 'edit category', 'delete category', 'list tag', 'create tag', 'edit tag', 'delete tag'
            ],
            'author'  => [
                'list article', 'create article', 'edit article', 'delete article', 'list category', 'create category', 'edit category', 'list tag', 'create tag', 'edit tag'
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

        // Create users
        $users = [
            [
                'name'          => 'Den Gavrishuk',
                'email'         => 'mishace282@gmail.com',
                'password'      => Hash::make('12345678'),
                'profile_image' => 'amperakoding.png',
                'role_id'       => 1,
            ],
            [
                'name'          => 'Author',
                'email'         => 'author@gmail.com',
                'password'      => Hash::make('12345678'),
                'profile_image' => 'muhazmi.png',
                'role_id'       => 2,
                'province_id'   => '16',
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole($userData['role_id']);
        }
    }
}
