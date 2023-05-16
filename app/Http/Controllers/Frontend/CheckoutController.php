<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
class CheckoutController extends Controller
{
    public function index()
    {
       
        return view('frontend.checkout.index');
    }
}
