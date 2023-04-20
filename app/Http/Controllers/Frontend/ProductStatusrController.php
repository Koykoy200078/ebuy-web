<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;


class ProductStatusrController extends Controller
{
    public function index()
    {
        $productCheck = OrderItem::where('product_user_id', auth()->user()->id)->get();
        return view('frontend.productstatus.index', compact('productCheck'));

    }
}
