<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
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
    public $payment_mode = 'Cash on Delivery';

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:121',
            'email' => 'required|email|max:121',
            'phone' => 'required|string|min:10|regex:/^\+?[0-9]+$/',
            'pincode' => 'required|string|max:6|min:4',
            'address' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $selectedIds = json_decode($request->query('selectedIds'));
        $trackingNo = 'ebuy-' . Str::random(10);

        $activitylog = Cart::where('user_id', auth()->user()->id)
            ->whereIn('id', Arr::flatten($selectedIds))->get('id');

        $carts = Cart::where('user_id', auth()->user()->id)
            ->whereIn('id', Arr::flatten($selectedIds))
            ->get();

        foreach ($carts as $cartItem) {
            $cartItem = $cartItem;
        }

        $product_id = $cartItem->product_id;
        $product_user_id =  Product::where('id', $product_id)->value('product_user_id');
        $seller_fullname =  User::where('id', $product_id)->value('name');
        $seller_email =  User::where('id', $product_id)->value('email');


        $order = Order::create([
            'user_id' => auth()->user()->id,
            'tracking_no' => $trackingNo,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'status_message' => 'in progress',
            'payment_mode' => $this->payment_mode,
            'payment_id' => null,
            'confirm' => 'pending',

            'product_user_id' =>  $product_user_id,
            'seller_fullname' =>  $seller_fullname,
            'seller_email' =>  $seller_email,
        ]);


        foreach ($carts as $cartItem) {
            $orderItems = Orderitem::create([
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,
                'product_id' => $cartItem->product_id,
                'product_user_id' =>  $product_user_id,
                'product_color_id' => $cartItem->product_color_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->selling_price,
                'status_message' => 'pending',
            ]);

            if ($cartItem->product_color_id != null) {
                $cartItem->productColor()->where('id', $cartItem->product_color_id)->decrement('quantity', $cartItem->quantity);
                $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
            } else {
                $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
            }

            $cartItem->delete();
        }

        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' Order Id:' .  $order->id . ". Product id ordered: [" . $activitylog . "]";

            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }

        $totalProductAmount = 0;
        foreach ($carts as $cartItem) {
            $totalProductAmount += $cartItem->product->selling_price * $cartItem->quantity;
        }

        $orderResource = new OrderResource($order);

        return response()->json([
            'order' => $orderResource,
            'totalProductAmount' => $totalProductAmount,
        ], 201);
    }
}
