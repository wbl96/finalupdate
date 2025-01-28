<?php

namespace App\Http\Controllers\Admins\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::guard('admin')->attempt($request->validated(), request('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['loginFailed' => trans('auth.failed')])->withInput();
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.showlogin');
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admins/dashboard';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
