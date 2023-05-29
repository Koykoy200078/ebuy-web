<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchProducts(Request $request)
    {
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $searchProducts = Product::where('name', 'like', '%' . $searchTerm . '%')
                ->latest()
                ->paginate(15);

            // Retrieve the image URL for each product
            foreach ($searchProducts as $product) {
                $product->image_url = url($product->productImages[0]->image);
                if ($product->quantity == 0) {
                    $product['quantity_status'] = 'Out of stock';
                } else {
                    $product['quantity_status'] = 'In stock';
                }
            }

            return response()->json(['searchResults' => $searchProducts], 200)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET');
        } else {
            return response()->json(['message' => 'Empty search parameter'], 400);
        }
    }
}
