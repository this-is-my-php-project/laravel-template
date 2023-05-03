<?php

namespace Database\Seeders;

use App\Constants\RoleConstant;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => RoleConstant::ADMIN['name'],
                'key' => RoleConstant::ADMIN['key'],
                'description' => 'Administrator of a workspace',
                'parent_id' => 1,
                'level' => 1,
                'is_active' => true,
                'is_global' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => RoleConstant::MANAGER['name'],
                'key' => RoleConstant::MANAGER['key'],
                'description' => 'Manager of a workspace',
                'parent_id' => 2,
                'level' => 2,
                'is_active' => true,
                'is_global' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => RoleConstant::MEMBER['name'],
                'key' => RoleConstant::MEMBER['key'],
                'description' => 'Member of a workspace',
                'parent_id' => 3,
                'level' => 3,
                'is_active' => true,
                'is_global' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Role::insert($roles);
    }
}
