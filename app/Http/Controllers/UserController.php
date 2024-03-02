<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UserWelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $role_id = Auth::user()->roles->first()->level;

            $users = User::whereHas('roles', function ($query) use ($role_id) {
                $query->where('level', '>', $role_id)->orderBy('level', 'asc');
            })->get();

            return view('user.user', ['users' => $users]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentUserRoleLevel = Auth::user()->roles->first()->level;
        $roles = Role::where('level', '>', $currentUserRoleLevel)->get();
        return view('user.userAdd', ['roles' => $roles, 'button' => 'Add', 'head_title' => 'Add']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();
            $inputData['isVerified'] = $request->has('isVerified') ? 1 : 0;

            if (empty($id)) {
                $password = \Str::random(10);
                $inputData['password'] = Hash::make($password);
            }

            // Retrieve the role ID
            $role_id = Role::where('name', $request->input('roles'))->pluck('id')->first();

            // Find or create the user
            $user = User::updateOrCreate(['id' => $id], $inputData);

            // Assign the role to the user
            $user->assignRole([$role_id]);

            // Retrieve the role object based on its ID
            $role = Role::find($role_id);

            // Check if the role has the permission
            $checkPermission = $role->hasPermissionTo('APP.access');

            if (empty($id)) {

                $userMailData = [
                    'email' => $user->email,
                    'password' => $password,
                    'isAppAccess' => $checkPermission
                ];

                // Mail::to($user->email)->send(new UserWelcomeMail($userMailData));
                // Send welcome email
                if ($this->sendWelcomeEmail($userMailData)) {
                    return redirect()->back()->with('message', 'User created successfully. Welcome email sent.');
                } else {
                    return redirect()->back()->with('message', 'User created successfully, but failed to send welcome email.');
                }
            }

            $message = empty($id) ? "User created successfully" : "User updated successfully";

            return redirect('users')->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $currentUserRoleLevel = Auth::user()->roles->first()->level;
            $roles = Role::where('level', '>', $currentUserRoleLevel)->get();

            $user = User::select('id', 'name', 'last_name', 'email', 'isVerified')->find($id);
            $role = $user->getRoleNames();
            $user['role'] = $role[0];

            return view('user.userAdd', ['roles' => $roles, 'button' => 'Update', 'head_title' => 'Edit', 'user' => $user]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Send welcome email to the user.
     *
     * @param  array  $userData
     * @return bool
     */
    protected function sendWelcomeEmail(array $userData): bool
    {
        try {
            Mail::to($userData['email'])->send(new UserWelcomeMail($userData));
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it in any other way
            return false;
        }
    }

    public function changeUserStatus(Request $request)
    {
        try {
            $userId = $request->input('id');
            $isVerified = $request->input('isVerified');
            $user = User::where('id', $userId)->update(['isVerified' => $isVerified]);

            $message = $isVerified ? "User enabled successfully." : "User disabled successfully.";

            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
}
