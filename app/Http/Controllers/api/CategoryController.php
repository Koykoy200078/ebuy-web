<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::select('id', 'name', 'slug')
            ->where('status', 0)
            ->get();
        return response()->json(['categories' => $categories], 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }
}
