<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProductsController extends Controller
{
    public function indexData(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = $user->name . ' clicked on Sell';

            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }

        $products = Product::where('product_user_id', auth()->user()->id)->get();

        return response()->json($products);
    }
}
