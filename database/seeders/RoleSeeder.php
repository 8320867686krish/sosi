<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $super_admin= Role::firstOrCreate(['name' => 'Super Admin','guard_name'=>'web']);
        $permissions = Permission::pluck('id','id')->all();
        $super_admin->syncPermissions($permissions);
    }
}
