<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function wishlistCount()
    {
        if (Auth::check()) {
            $wishlistCount = Wishlist::where('user_id', auth()->user()->id)->count();
        } else {
            $wishlistCount = 0;
        }

        return response()->json([
            'wishlistCount' => $wishlistCount
        ]);
    }

    public function wishlistView(Request $request)
    {
        $wishlist = Wishlist::with('product.productImages', 'product.category')
            ->where('user_id', $request->user()->id)
            ->get();

        $wishlistItems = $wishlist->filter(function ($wishlistItem) {
            return $wishlistItem->product !== null;
        })->map(function ($wishlistItem) {
            $product = $wishlistItem->product;

            $productImage = $product->productImages->first();
            $imageUrl = $productImage ? asset($productImage->image) : null;

            return [
                'id' => $wishlistItem->id,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ],
                    'slug' => $product->slug,
                    'selling_price' => $product->selling_price,
                    'image_url' => $imageUrl,
                ],
            ];
        });

        return response()->json([
            'data' => $wishlistItems,
        ]);
    }

    public function destroy(Request $request, $wishlistId)
    {
        Wishlist::where('user_id', $request->user()->id)->where('id', $wishlistId)->delete();
        return response()->json([
            'message' => 'Wishlist item removed successfully',
        ]);
    }
}
