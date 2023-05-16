<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddToCartController extends Controller
{
    public function cart()
    {
        $cartCount = 0;

        if (Auth::check()) {
            $cartCount = Cart::where('user_id', auth()->user()->id)->count();
        }

        return response()->json(['cart_count' => $cartCount], 200);
    }

    // public function cartShow(Request $request)
    // {
    //     $cart = Cart::where('user_id', $request->user()->id)->get();
    //     $totalPrice = 0;
    //     $cartData = [];

    //     foreach ($cart as $cartItem) {
    //         $product = $cartItem->product;
    //         $image_url = url($product->productImages[0]->image);
    //         $item_name = $product->name;
    //         $product_price = $product->selling_price * $cartItem->quantity;
    //         $totalPrice += $product->selling_price * $cartItem->quantity;

    //         // Get product color information
    //         $productColors = $product->productColors->map(function ($item) use ($cartItem) {
    //             if ($item->id === $cartItem->product_color_id) {
    //                 return [
    //                     'product_color_id' => $item->id,
    //                     'color_name' => $item->color->name,
    //                     'quantity' => $item->quantity,
    //                 ];
    //             }
    //         })->filter();

    //         $productColors = collect($productColors)->values()->first();


    //         // Add product color information to cart data
    //         $cartData[] = [
    //             'cart_id' => $cartItem->id,
    //             'user_id' => $cartItem->user_id,
    //             'product_id' => $cartItem->product_id,
    //             'product_color_id' => $cartItem->product_color_id,
    //             'quantity' => $cartItem->quantity,
    //             'created_at' => $cartItem->created_at,
    //             'updated_at' => $cartItem->updated_at,
    //             'item_name' => $item_name,
    //             'image_url' => $image_url,
    //             'product_price' => $product_price,
    //             'product_colors' => $productColors,
    //         ];
    //     }

    //     return response()->json([
    //         'cart' => $cartData,
    //         'totalPrice' => $totalPrice
    //     ]);
    // }

    public function cartShow(Request $request)
    {
        $selectedIds = $request->input('selectedIds', []);
        $cart = Cart::where('user_id', auth()->user()->id)
            ->join('users', 'carts.product_user_id', '=', 'users.id')
            ->orderBy('product_user_id', 'asc')
            ->get()
            ->groupBy('product_user_id');
        $totalPrice = 0;
        $cartGrouped = [];
        foreach ($cart as $productUserId => $cartItems) {
            $totalPriceForGroup = 0;
            foreach ($cartItems as $cartItem) {
                if (in_array($cartItem->id, $selectedIds)) {
                    $totalPriceForGroup += $cartItem->product->selling_price * $cartItem->quantity;
                    $totalPrice += $cartItem->product->selling_price * $cartItem->quantity;
                }
                $product = $cartItem->product;
                $item_name = $product->name;
                $image_url = url($product->productImages[0]->image);
                $product_price = $product->selling_price;
                // Get product color information
                $productColors = $product->productColors->map(function ($item) use ($cartItem) {
                    if ($item->id === $cartItem->product_color_id) {
                        return [
                            'product_color_id' => $item->id,
                            'color_name' => $item->color->name,
                            'quantity' => $item->quantity,
                        ];
                    }
                })->filter();
                $productColors = collect($productColors)->values()->first();
                $cartItem->productColors = $productColors;
            }
            $userDetails = UserDetail::where('user_id', $productUserId)->first();
            $userDetails2 = User::where('id', $productUserId)->first();
            $cartGrouped[] = [
                'product_user_id' => $productUserId,
                'storename' => $userDetails ? $userDetails->storename : $userDetails2->name,
                'items' => $cartItems->map(function ($item) use (
                    
                    $product,
                    $item_name,
                    $image_url,
                    $product_price
                ) {
                    $productColors = $item->productColors;
                    return collect($item)->except(['created_at', 'updated_at', 'name', 'email', 'email_verified_at', 'password', 'address', 'phone', 'birthday', 'gender', 'remember_token', 'role_as', 'auth_type'])->merge(compact(
                        'product',
                        'item_name',
                        'image_url',
                        'product_price',
                        'productColors'
                    ))->toArray();
                }),
                'totalPrice' => $totalPriceForGroup
            ];
        }
        return response()->json([
            'cart' => $cartGrouped,
            'totalPrice' => $totalPrice
        ]);
    }




    public function decrementQuantity(Request $request, $cartId)
    {
        $cartData = Cart::where('id', $cartId)->where('user_id', $request->user()->id)->first();

        if ($cartData) {
            if ($cartData->quantity >= 1) {
                $cartData->decrement('quantity');

                return response()->json([
                    'message' => 'Quantity Updated',
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Quantity cannot be less than 1',
                    'status' => 200
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Something Went Wrong!',
                'status' => 404
            ]);
        }
    }

    public function removeCartItem(Request $request, $cartId)
    {
        $cartRemoveData = Cart::where('user_id', $request->user()->id)->where('id', $cartId)->first();

        if ($cartRemoveData) {
            $cartRemoveData->delete();

            return response()->json([
                'message' => 'Cart Item Removed Successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 500
            ]);
        }
    }

    public function incrementQuantity(Request $request, $cartId)
    {
        $cartData = Cart::where('id', $cartId)->where('user_id', $request->user()->id)->first();

        if ($cartData) {
            if ($cartData->productColor()->where('id', $cartData->product_color_id)->exists()) {
                //Color Product Quantity
                $productColor = $cartData->productColor()->where('id', $cartData->product_color_id)->first();

                if ($productColor->quantity > $cartData->quantity) {
                    $cartData->increment('quantity');

                    return response()->json([
                        'message' => 'Quantity Updated',
                        'status' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Only ' . $productColor->quantity . ' Quantity Available',
                        'status' => 200
                    ]);
                }
            } else {
                //Normal Product Quantity
                if ($cartData->product->quantity > $cartData->quantity) {
                    $cartData->increment('quantity');

                    return response()->json([
                        'message' => 'Quantity Updated',
                        'status' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Only ' . $cartData->product->quantity . ' Quantity Available',
                        'status' => 200
                    ]);
                }
            }
        } else {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 404
            ]);
        }
    }

    public function addToCart(Request $request, int $productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Please Login to Continue'], 401);
        }

        $product = Product::findOrFail($productId);

        if (!$product || $product->status != '0') {
            return response()->json(['message' => 'Product does not exist or is unavailable'], 404);
        }

        if ($product->product_user_id == $user->id) {
            return response()->json(['message' => 'You cannot buy your own product'], 401);
        }

        $productColorId = $request->input('product_color_id');
        $quantityCount = $request->input('quantity_count');

        if ($product->productColors()->count() > 1) {
            if (!$productColorId) {
                return response()->json(['message' => 'Select Your Product Color'], 404);
            }

            $productColor = $product->productColors()->where('id', $productColorId)->first();

            if (!$productColor) {
                return response()->json(['message' => 'Product color does not exist'], 404);
            }

            if ($productColor->quantity < $quantityCount) {
                return response()->json(['message' => 'Only ' . $productColor->quantity . ' Quantity Available'], 404);
            }
        } else {
            if ($product->quantity < $quantityCount) {
                return response()->json(['message' => 'Only ' . $product->quantity . ' Quantity Available'], 404);
            }
        }

        if (Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_color_id', $productColorId)
            ->exists()
        ) {
            return response()->json(['message' => 'Product Already Added'], 200);
        }

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'product_user_id' => $product->product_user_id,
            'product_color_id' => $productColorId,
            'quantity' => $quantityCount
        ]);

        return response()->json(['message' => 'Product Added to Cart'], 200);
    }
}
