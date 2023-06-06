<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\ActivityLog;

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
        if(Auth::user()->role_as == '2'){
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' Login';
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return redirect('/admin/dashboard')->with('message', 'Welcome to Dashboard');
        }
        else{
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' Login';
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return redirect('/')->with('status', 'Logged In Successfuly');
          
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
