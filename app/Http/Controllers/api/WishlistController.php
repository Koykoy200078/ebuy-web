<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
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

    public function addToWishlist($productId)
    {
        $user = Auth::user();

        if ($user) {
            $verify = Product::where('product_user_id', $user->id)->value('product_user_id');
            $Productverify = Product::where('id', $productId)->value('product_user_id');
            if ($Productverify != $verify) {
                if (Wishlist::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
                    return response()->json([
                        'message' => 'Already added to wishlist'
                    ], 409);
                } else {
                    Wishlist::create([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                    ]);

                    $description = '' . $user->name . ' added Product id:' . $productId . ' to the Wishlist';
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'description' => $description,
                    ]);

                    return response()->json([
                        'message' => 'Wishlist added successfully'
                    ], 200);
                }
            } else {
                return response()->json([
                    'message' => 'You cannot buy your own product'
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Please Login to Continue'
            ], 401);
        }
    }
}
