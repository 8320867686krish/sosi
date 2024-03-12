<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\otpVerification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $res= $request->authenticate();
        if(@$res->original['isOtp'] == 1){
            $sessionId = session()->getId();
            $code = random_int(100000, 999999);
            $sessionPayload = session()->all();
            $token = $sessionPayload['_token'];
            $insertData = ['session_id' =>$sessionId,'isVerify' => 0,'code' => $code,'token'=> $token,'email' => $request->input('email')];
            $otp = otpVerification::updateOrCreate(['token'=>$token], $insertData);
            return response()->json(['isOtp' => 1,'status'=>true,'deviceId'=> $sessionId,'code' => $code],200);
        }else{
            $request->session()->regenerate();
            return response()->json(['isOtp' => 0,'status'=>true],200);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('logout' . 'user logout');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
