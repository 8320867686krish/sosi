<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            // Validate the request inputs
            $request->validate([
                'current_password' => ['required'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            // Check if the current password matches the one stored in the database
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages(['current_password' => 'The provided current password is incorrect.']);
            }

            // Update the user's password
            $user->password = Hash::make($request->password);
            $user->save();

            // Logout the user
            Auth::guard('web')->logout();

            // Invalidate the current session
            Session::invalidate();

            // Regenerate the CSRF token
            $request->session()->regenerateToken();

            // Redirect the user to the login page with a success message
            return Redirect::to('/login')->with('message', 'Password changed successfully. Please log in with your new password.');
        } catch (ValidationException $e) {
            // If validation fails, redirect back with errors
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            // If an unexpected error occurs, redirect back with a generic error message
            return Redirect::back()->with('error', 'An error occurred while changing the password. Please try again later.');
        }
    }
}
