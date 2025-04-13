<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            (object)[
                'name' => 'Admin',
                'permissions' => [],
            ],
            (object)[
                'name' => 'Tester',
                'permissions' => [],
            ],
            (object)[
                'name' => 'Manager',
                'permissions' => [],
            ],
            (object)[
                'name' => 'Developer',
                'permissions' => [],
            ],
        ];

        foreach ($roles as $role) {
            $existed_roles = Role::get()->pluck('name')->toArray();
            if (!in_array($role->name, $existed_roles)) {
                $created_role = Role::create(['name' => $role->name]);
                foreach ($role->permissions as $permission) {
                    $created_role->givePermissionTo($permission);
                }
            }
        }
    }
}
