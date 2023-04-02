<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::select('id', 'name', 'slug', 'image')
            ->where('status', 0)
            ->get();

        $categories = $categories->map(function ($category) {
            $category->image_url = url($category->image);
            return $category;
        });
        return response()->json(['categories' => $categories], 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }
}
