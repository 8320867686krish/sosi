<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin', 
            'email' => 'super@admin',
            'password' => Hash::make('javed1234')
        ]);
        $role = Role::where('name' ,'Super Admin')->pluck('id')->first();
        $superAdmin->assignRole([$role]);
    
       // $superAdmin->assignRole('admin');

    }
}
