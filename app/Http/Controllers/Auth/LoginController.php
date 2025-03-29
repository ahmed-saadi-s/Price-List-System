<?php

namespace App\Http\Controllers\Auth;
// Import the LoginRequest to validate the incoming data before attempting authentication
use App\Http\Requests\Auth\LoginRequest; 
use Illuminate\Support\Facades\Auth;

class LoginController 
{
    // Handle the admin login process .
    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('success', __('messages.login_success'));
        }
        return redirect()->route('login.form')->withErrors(['login' => __('messages.login_failed')]);
    }
}
