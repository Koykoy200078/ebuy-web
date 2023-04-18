<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
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

    public function cartShow(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->get();
        $totalPrice = 0;
        $cartData = [];

        foreach ($cart as $cartItem) {
            $product = $cartItem->product;
            $image_url = url($product->productImages[0]->image);
            $item_name = $product->name;
            $product_price = $product->selling_price * $cartItem->quantity;
            $totalPrice += $product->selling_price * $cartItem->quantity;

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


            // Add product color information to cart data
            $cartData[] = [
                'cart_id' => $cartItem->id,
                'user_id' => $cartItem->user_id,
                'product_id' => $cartItem->product_id,
                'product_color_id' => $cartItem->product_color_id,
                'quantity' => $cartItem->quantity,
                'created_at' => $cartItem->created_at,
                'updated_at' => $cartItem->updated_at,
                'item_name' => $item_name,
                'image_url' => $image_url,
                'product_price' => $product_price,
                'product_colors' => $productColors,
            ];
        }

        return response()->json([
            'cart' => $cartData,
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

    public function addToCart(Request $request, $productId)
    {
        $verify = Product::where('product_user_id', Auth::id())->value('product_user_id');
        $Productverify = Product::where('id', $productId)->value('product_user_id');

        if ($Productverify != $verify) {
            if (Auth::check()) {
                $product = Product::find($productId);
                if ($product && $product->status === 0) {
                    // Check for product color quantity and add to cart
                    if ($product->productColors()->count() > 1) {
                        $productColorId = $request->input('product_color_id');
                        if (!$productColorId) {
                            return response()->json([
                                'message' => 'Select Your Product Color'
                            ], 404);
                        }
                        $productColor = $product->productColors()->where('id', $productColorId)->first();
                        if (!$productColor) {
                            return response()->json([
                                'message' => 'Invalid Product Color'
                            ], 404);
                        }
                        if (Cart::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->where('product_color_id', $productColorId)
                            ->exists()
                        ) {
                            return response()->json([
                                'message' => 'Product Already Added to Cart'
                            ], 200);
                        }
                        if ($productColor->quantity > 0) {
                            if ($productColor->quantity >= $request->input('quantity', 1)) {
                                // Insert Product to Cart
                                Cart::create([
                                    'user_id' => Auth::id(),
                                    'product_id' => $productId,
                                    'product_color_id' => $productColorId,
                                    'quantity' => $request->input('quantity', 1)
                                ]);

                                return response()->json([
                                    'message' => 'Product Added to Cart'
                                ], 200);
                            } else {
                                return response()->json([
                                    'message' => 'Only ' . $productColor->quantity . ' Quantity Available'
                                ], 404);
                            }
                        } else {
                            return response()->json([
                                'message' => 'Out of Stock'
                            ], 404);
                        }
                    } else {
                        if (Cart::where('user_id', Auth::id())->where('product_id', $productId)->exists()) {
                            return response()->json([
                                'message' => 'Product Already Added to Cart'
                            ], 200);
                        } else {
                            if ($product->quantity > 0) {
                                if ($product->quantity >= $request->input('quantity', 1)) {
                                    // Insert Product to Cart
                                    Cart::create([
                                        'user_id' => Auth::id(),
                                        'product_id' => $productId,
                                        'quantity' => $request->input('quantity', 1)
                                    ]);

                                    return response()->json([
                                        'message' => 'Product Added to Cart'
                                    ], 200);
                                } else {
                                    return response()->json([
                                        'message' => 'Only ' . $product->quantity . ' Quantity Available'
                                    ], 404);
                                }
                            } else {
                                return response()->json([
                                    'message' => 'Out of Stock'
                                ], 404);
                            }
                        }
                    }
                } else {
                    return response()->json([
                        'message' => 'Product does not exist or is not available'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Please Login to add to cart'
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'You cannot buy your own product'
            ], 401);
        }
    }
}
