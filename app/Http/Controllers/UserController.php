<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Jobs\sendUserRegisterMail;
use App\Models\otpVerification;
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
    public function otp(){

        return view('auth.otp');

    }
    public function verifyOtp(Request $request){
        $sessionId = session()->getId();
        $post = $request->input();
        $sessionPayload = session()->all();
        $token = $sessionPayload['_token'];
        $otpVerify = otpVerification::where(['session_id' => $sessionId,'code' => $post['code']])->first();
        if($otpVerify){
            $user = User::where(['email' => $otpVerify->email])->first();

            if ($user) {
                Auth::login($user);
                $otpVerify->delete();
                $request->session()->regenerate();
                return response()->json(['status'=>true],200);
            }else{
                return response()->json(['status' => false, 'message' => 'User not found'], 200);

            }
        }else{
            return response()->json(['status'=>false,'message' => 'invalid otp'],200);
        }
    }
    public function index()
    {
        try {
            $role_id = Auth::user()->roles->first()->level;

            if($role_id != 1){
                $users = User::whereHas('roles', function ($query) use ($role_id) {
                    $query->where('level', '>', $role_id)->orderBy('level', 'asc');
                })->get();
            } else {
                $users = User::get();
            }

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

        if($currentUserRoleLevel != 1){
            $roles = Role::where('level', '>', $currentUserRoleLevel)->get();
        }else{
            $roles = Role::get();
        }

        return view('user.userAdd', ['roles' => $roles, 'button' => 'Save', 'head_title' => 'Add']);
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
                $first_three_chars = substr($request->input('name'), 0, min(3, strlen($request->input('name'))));
                $password = "sosi{$first_three_chars}123";
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
                    'name'=> $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'password' => $password,
                    'isAppAccess' => $checkPermission
                ];

                if ($this->sendWelcomeEmail($userMailData)) {
                    // return redirect('users')->with('message', 'User created successfully. Welcome email sent.');
                    return response()->json(['isStatus' => true, 'message' => 'User created and welcome email sent successfully.']);
                } else {
                    // return redirect('users')->with('message', 'User created successfully, but failed to send welcome email.');
                    return response()->json(['isStatus' => true, 'message' => 'User created successfully, but failed to send welcome email.']);
                }
            }

            $message = empty($id) ? "User created successfully" : "User updated successfully";

            // return redirect('users')->with('message', $message);
            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (\Throwable $th) {
            // return back()->withError($th->getMessage())->withInput();
            dd($th->getMessage());
            return response()->json(['isStatus' => false, 'message' => $th->getMessage()]);
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

            if($currentUserRoleLevel != 1){
                $roles = Role::where('level', '>', $currentUserRoleLevel)->get();
            } else {
                $roles = Role::get();
            }

            $user = User::find($id);
            $role = $user->getRoleNames();
            $user['role'] = $role[0];
            $user['roleLevel'] = $currentUserRoleLevel;
            unset($user->password, $user->created_at, $user->updated_at);

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
            dispatch(new sendUserRegisterMail($userData));

          //  Mail::to($userData['email'])->send(new UserWelcomeMail($userData));
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
