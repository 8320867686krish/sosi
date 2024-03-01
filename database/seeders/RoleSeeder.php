<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin= Role::firstOrCreate(['name' => 'Super Admin','level'=>1]);
        $permissions = Permission::pluck('id','id')->all();
        $super_admin->syncPermissions($permissions);
    }
}
