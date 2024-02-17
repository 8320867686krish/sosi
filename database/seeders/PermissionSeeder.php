<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'ships' => [
                'actions' => ['list', 'create', 'edit', 'delete'],
            ],
            'roles' => [
                'actions' => ['list', 'create', 'edit', 'delete'],
            ],
            'permissions' => [
                'actions' => ['list', 'create', 'edit', 'delete'],
            ],
          
        ];

        foreach ($permissions as $entity => $permissionData) {
            $actions = $permissionData['actions'];
            Permission::firstOrCreate(['name' => $entity, 'group_type' => 'main']);
            foreach ($actions as $action) {
                $subPermission = "$entity.$action";
                Permission::firstOrCreate(['name' => $subPermission, 'group_type' => $entity]);
            }
        }
    }
}
