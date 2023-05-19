<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class authcontroller extends Controller
{
    // Github

    public function githubredirect(Request $request)
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubcallback(Request $request)
    {
        try {
            $userdata = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to fetch user data from Github']);
        }

        $validator = Validator::make(['email' => $userdata->email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = User::where('email', $userdata->email)->where('auth_type', 'github')->first();

        if ($user) {
            Auth::login($user);
            return redirect('/');
        }

        $newUser = $this->createNewGithubUser($userdata);

        Auth::login($newUser);
        return redirect('/');
    }

    private function createNewGithubUser($userdata)
    {
        $uuid = Str::uuid()->toString();

        $user = new User();
        $user->name = $userdata->name;
        $user->email = $userdata->email;
        $user->password = Hash::make($uuid . now());
        $user->auth_type = 'github';
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }


    // Google
    public function googleredirect(Request $request)
    {

        return Socialite::driver('google')->redirect();
    }

    public function googlecallback(Request $request)
    {
        try {
            $userdata = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to fetch user data from Google']);
        }

        $validator = Validator::make(['email' => $userdata->email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = User::where('email', $userdata->email)->where('auth_type', 'google')->first();

        if ($user) {
            Auth::login($user);
            return redirect('/');
        }
        $defaultpass = 'default123';

        $newUser = $this->createNewUser($userdata, $defaultpass);

        Auth::login($newUser);
        return redirect('/')->with('message2', 'Your default Password is <strong>default123</strong>');
    }

    private function createNewUser($userdata, $defaultpass)
    {
        
        $uuid = Str::uuid()->toString();
        // return view ($defaultpass);
        $user = new User();
        $user->name = $userdata->name;
        $user->email = $userdata->email;
        $user->password = Hash::make($defaultpass);
        $user->auth_type = 'google';
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }
}
