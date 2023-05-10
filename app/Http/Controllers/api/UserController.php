<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUserDetails()
    {
        $user = User::findOrFail(Auth::user()->id);
        $userDetails = $user->userDetail;

        return response()->json([
            'username' => $user->name,
            'storename' => $userDetails ? $userDetails->storename : null,
            'email' => $user->email,
            'phone' => $userDetails ? $userDetails->phone : null,
            'pin_code' => $userDetails ? $userDetails->pin_code : null,
            'address' => $userDetails ? $userDetails->address : null,
        ]);
    }

    public function updateUserDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'storename' => ['required', 'string'],
            'phone' => ['required', 'min:10', 'max:11'],
            'pin_code' => ['required', 'max:4'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::findOrFail(Auth::user()->id);
        $user->update([
            'name' => $request->username
        ]);

        $user->userDetail()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'storename' => $request->storename,
                'phone' => $request->phone,
                'pin_code' => $request->pin_code,
                'address' => $request->address,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'User Profile Updated'
        ], 200)->header('Content-Type', 'application/json')->header('X-Request-Id', uniqid());
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8']
        ]);

        $currentPasswordStatus = Hash::check($request->current_password, auth()->user()->password);
        if ($currentPasswordStatus) {

            User::findOrFail(Auth::user()->id)->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'Password Updated Successfully'], 200);
        } else {

            return response()->json(['message' => 'Current Password does not match.'], 400);
        }
    }
}
