<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on Cart';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        return view('frontend.cart.index');
    }
}
