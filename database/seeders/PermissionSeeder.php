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

            'permissions' => [
                'actions' => [],
                'is_show' => 0,
            ],
            'ship_owners' => [
                'actions' => ['read' => 0, 'add' => 0,'edit' => 0, 'remove' => 0],
                "is_show" => 1
            ],
            'projects' => [
                'actions' => ['read' => 0, 'add' => 0, 'edit' => 0,'remove' => 0],
                "is_show" => 1
            ],
            'roles' => [
                'actions' => ['read' => 0, 'add' => 0, 'edit' => 0,'remove' => 0],
                "is_show" => 1
            ],
            'users' => [
                'actions' => ['read' => 0, 'add' => 0,  'edit' => 0],
                "is_show" => 1
            ],

            'APP' => [
                'actions' => ['access' => 0],
                'is_show' => 0,
            ],

        ];

        foreach ($permissions as $entity => $permissionData) {
            $actions = $permissionData['actions'];
            $is_show = $permissionData['is_show'];
            Permission::firstOrCreate(['name' => $entity, 'group_type' => 'main', 'is_show' => $is_show]);
            if (@$actions) {
                foreach ($actions as $key => $action) {
                    $subPermission = "$entity.$key";
                    $sub_is_show = "$action";
                    Permission::firstOrCreate(['name' => $subPermission, 'group_type' => $entity, 'is_show' => $sub_is_show]);
                }
            }
        }
    }
}
