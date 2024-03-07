<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::get();
        return view('roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create', ['head_title' => 'Add', 'button' => 'Save']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::updateOrCreate(['id' => $request->input('id')], ['name' => $request->input('name'),'level'=>$request->input('level')]);

            if (!empty($request->input('permissions'))) {
                $permissions = $request->input('permissions');
                $permissions_main = array_keys($permissions);
                $permissions_sub = array_values($permissions);
                $merge_permission = array_merge($permissions_main, $permissions_sub);

                $role->syncPermissions($merge_permission);
            }else{
                $role->syncPermissions([]);
            }

            $message = empty($id) ? "Roles with permission added successfully" : "Roles with permission updated successfully";

            return redirect('roles')->with('message', $message);
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $roles = Role::select('id', 'name', 'level')->find($id);
            $role_permissions = $roles->permissions->pluck('id')->toArray();
            $permissions = Permission::all();
            return view('roles.create', ["role" => $roles, 'head_title' => 'Edit', 'button' => 'Update', 'role_permissions' => $role_permissions, 'permissions' => $permissions]);
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $roles = Role::withCount('users')->findOrFail($id);

            if (!$roles->users_count) {
                $roles->delete();
                return redirect('roles')->with('message', 'Role deleted successfully');
            } else {
                return redirect('roles')->with("error", "This role is assigned to the user. Please change this user's role first");
            }
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
}
