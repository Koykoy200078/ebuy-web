<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
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

    //     // $image_url = url($product->productImages[0]->image);
    //     foreach ($cart as $cartItem) {
    //         $product = $cartItem->product;
    //         $image_url = url($product->productImages[0]->image); // add image_url link
    //         $totalPrice += $product->selling_price * $cartItem->quantity;
    //     }

    //     return response()->json([
    //         'cart' => $cart,
    //         'image_url' => $image_url,
    //         'totalPrice' => $totalPrice
    //     ]);
    // }
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

            $cartData[] = [
                'id' => $cartItem->id,
                'user_id' => $cartItem->user_id,
                'product_id' => $cartItem->product_id,
                'product_color_id' => $cartItem->product_color_id,
                'quantity' => $cartItem->quantity,
                'created_at' => $cartItem->created_at,
                'updated_at' => $cartItem->updated_at,
                'item_name' => $item_name,
                'image_url' => $image_url,
                'product_price' => $product_price,
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
}
