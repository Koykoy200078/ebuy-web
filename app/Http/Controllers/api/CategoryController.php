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

    public function show($category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();

        if ($category) {
            $products = $category->products;
            $products = $products->map(function ($product) {
                $product->image_url = url($product->productImages[0]->image);
                return $product;
            });
            return response()->json(['message' => 'Success', 'data' => $products], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
