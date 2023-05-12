<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
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
    // protected $redirectTo = RouteServiceProvider::HOME;

    protected function authenticated()
    {
        $userRole = Auth::user()->role_as;
        if ($userRole === 1 || $userRole === 2) {
            return redirect('/admin/dashboard')->with('message', 'Welcome to Dashboard');
        } else {
            return redirect('/home')->with('status', 'Logged In Successfully');
            // if (Auth::user()->email_verified_at) {
            //     return redirect('/home')->with('status', 'Logged In Successfuly');
            // } else {
            //     Auth::logout();
            //     return redirect()->route('login')->with('error', 'Your account is not verified. Please verify your email.');
            // }
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
