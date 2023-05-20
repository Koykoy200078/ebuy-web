<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\PlaceOrderMailable;
use App\Mail\SellerInvoiceOrderMailable;
use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    // public function store(Request $request)
    // {
    //     $user = $request->user();
    //     $carts = Cart::where('user_id', $user->id)->get();
    //     $totalProductAmount = 0;

    //     foreach ($carts as $cartItem) {
    //         $totalProductAmount += $cartItem->product->selling_price * $cartItem->quantity;
    //     }

    //     $request->validate([
    //         'fullname' => 'required|string|max:121',
    //         'email' => 'required|email|max:121',
    //         'phone' => 'required|string|max:11|min:10',
    //         'pincode' => 'required|string|max:6|min:4',
    //         'address' => 'required|string|max:500'
    //     ]);

    //     $payment_mode = $request->input('payment_mode');
    //     $payment_id = $request->input('payment_id');

    //     if ($payment_mode == 'Paid by Paypal') {
    //         $payment_id = $request->input('payment_id');
    //     } else {
    //         $payment_id = null;
    //     }

    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'tracking_no' => 'ebuy-' . Str::random(10),
    //         'fullname' => $request->input('fullname'),
    //         'email' => $request->input('email'),
    //         'phone' => $request->input('phone'),
    //         'pincode' => $request->input('pincode'),
    //         'address' => $request->input('address'),
    //         'status_message' => 'in progress',
    //         'payment_mode' => $payment_mode,
    //         'payment_id' => $payment_id,
    //         'confirm' => 'pending', // Provide a default value for the confirm field

    //         $product_id = $cartItem->product_id,
    //         $test =  Product::where('id', $product_id)->value('product_user_id'),
    //         'product_user_id' =>  $test,

    //         $product_id = $cartItem->product_id,
    //         $test =  Product::where('id', $product_id)->value('product_user_id'),
    //         $test =  User::where('id', $test)->value('name'),
    //         'seller_fullname' =>  $test,
    //         $product_id = $cartItem->product_id,
    //         $test =  Product::where('id', $product_id)->value('product_user_id'),
    //         $test =  User::where('id', $test)->value('email'),
    //         'seller_email' =>  $test,
    //         $product_id = $cartItem->product_id,
    //         $test =  Product::where('id', $product_id)->value('product_user_id'),
    //         $test =  UserDetail::where('user_id', $test)->value('phone'),
    //         'seller_phone' =>  $test,

    //     ]);

    //     foreach ($carts as $cartItem) {
    //         $orderItems = Orderitem::create([
    //             'order_id' => $order->id,
    //             'user_id' => auth()->user()->id,
    //             'product_id' => $cartItem->product_id,
    //             'product_color_id' => $cartItem->product_color_id,
    //             $product_id = $cartItem->product_id,
    //             $test =  Product::where('id', $product_id)->value('product_user_id'),
    //             'product_user_id' =>  $test,
    //             'quantity' => $cartItem->quantity,
    //             'price' => $cartItem->product->selling_price,
    //             'status_message' => 'pending',
    //         ]);

    //         if ($cartItem->product_color_id != NULL) {
    //             $cartItem->productColor()->where('id', $cartItem->product_color_id)->decrement('quantity', $cartItem->quantity);
    //             $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
    //         } else {
    //             $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
    //         }
    //     }

    //     // Mail::to($order->email)->send(new PlaceOrderMailable($order));

    //     Cart::where('user_id', $user->id)->delete();

    //     return response()->json(['message' => 'Order placed successfully']);
    // }
}
