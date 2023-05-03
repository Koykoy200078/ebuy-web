<?php

namespace App\Http\Livewire\Frontend\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Orderitem;
use Livewire\Component;
use App\Mail\PlaceOrderMailable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

class CheckoutShow extends Component
{
    public $carts, $totalProductAmount = 0;

    public $fullname, $email, $phone, $pincode, $address, $payment_mode = NULL, $payment_id = NULL;

    protected $listeners = [
        'validationForAll',
        'transactionEmit' => 'paidOnlineOrder'
    ];
    public function mount()
    {
        $selectedIds = json_decode(request()->query('selectedIds'));
        // $selectedIds will now contain the array of selected IDs from the previous page
        $this->selectedIds = $selectedIds;
    }
    public function paidOnlineOrder($value)
    {
        $this->payment_id = $value;
        $this->payment_mode = 'Paid by Paypal';

        $codOrder = $this->placeOrder();
        if ($codOrder) {
            $ids = $this->selectedIds;
            Cart::where('user_id', auth()->user()->id)
            ->whereIn('id',  Arr::flatten($ids))
            ->delete();

            //
            try {
                $order = Order::findOrFail($codOrder->id);
                Mail::to("$order->email")->send(new PlaceOrderMailable($order));
                // Mail sent successfully
            } catch (\Exception $e) {
                // Something went wrong
                // return redirect()->to('thank-you')->with('message', 'Something went wrong!'.$e);

            }

            session()->flash('message', 'Order Placed Successfuly');
            $this->dispatchBrowserEvent('message', [
                'text' => 'Order Place Successfuly',
                'type' => 'success',
                'status' => 200
            ]);
            return redirect()->to('thank-you');
        } else {
            $this->dispatchBrowserEvent('message', [
                'text' => 'Something went wrong!',
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function validationForAll()
    {
        $this->validate();
    }

    public function rules()
    {
        return [
            'fullname' => 'required|string|max:121',
            'email' => 'required|email|max:121',
            'phone' => 'required|string|max:11|min:10',
            'pincode' => 'required|string|max:6|min:4',
            'address' => 'required|string|max:500'
        ];
    }

    public function placeOrder()
    {
        
        // foreach ($this->carts as $cartItem) {
        //     $product_id = $cartItem->product_id;
        // // return view($product_id);

        //     $test =  Product::where('id', $product_id)->pluck('product_user_id');
        // }
        // return view($test);


        $this->validate();

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'tracking_no' => 'ebuy-' . Str::random(10),
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'pincode' => $this->pincode,
            'address' => $this->address,
            'status_message' => 'in progress',
            'payment_mode' => $this->payment_mode,
            'payment_id' => $this->payment_id,
        ]);

        foreach ($this->carts as $cartItem) {
            $orderItems = Orderitem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_color_id' => $cartItem->product_color_id,
                $product_id = $cartItem->product_id,
                $test =  Product::where('id', $product_id)->value('product_user_id'),
                'product_user_id' =>  $test,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->selling_price,
                'status_message' => 'pending'

            ]);

            if ($cartItem->product_color_id != NULL) {
                $cartItem->productColor()->where('id', $cartItem->product_color_id)->decrement('quantity', $cartItem->quantity);
                $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
            } else {
                $cartItem->product()->where('id', $cartItem->product_id)->decrement('quantity', $cartItem->quantity);
            }
        }

        return $order;
    }

    public function codOrder()
    {

        $this->payment_mode = 'Cash on Delivery';

        $codOrder = $this->placeOrder();
        if ($codOrder) {
            $ids = $this->selectedIds;
            Cart::where('user_id', auth()->user()->id)
            ->whereIn('id',  Arr::flatten($ids))
            ->delete();
            //
            try {
                $order = Order::findOrFail($codOrder->id);
                Mail::to("$order->email")->send(new PlaceOrderMailable($order));
                // Mail sent successfully
            } catch (\Exception $e) {
                // Something went wrong
            }
            //

            session()->flash('message', 'Order Placed Successfuly');
            $this->dispatchBrowserEvent('message', [
                'text' => 'Order Place Successfuly',
                'type' => 'success',
                'status' => 200
            ]);
            return redirect()->to('thank-you');
        } else {
            $this->dispatchBrowserEvent('message', [
                'text' => 'Something went wrong!',
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function totalProductAmount()
    {
        $ids = $this->selectedIds;
    //    dd($selectedIds);
        $this->totalProductAmount = 0;
        $this->carts = Cart::where('user_id', auth()->user()->id)
        ->whereIn('id',  Arr::flatten($ids))
        ->get();
        foreach ($this->carts as $cartItem) {
            $this->totalProductAmount += $cartItem->product->selling_price * $cartItem->quantity;
        }

        return $this->totalProductAmount;
    }

    public function render()
    {
        $this->fullname = auth()->user()->name;
        $this->email = auth()->user()->email;

        $this->phone = auth()->user()->userDetail->phone ?? '';
        $this->pincode = auth()->user()->userDetail->pin_code ?? '';
        $this->address = auth()->user()->userDetail->address ?? '';

        $this->totalProductAmount = $this->totalProductAmount();
        return view('livewire.frontend.checkout.checkout-show', [
            'totalProductAmount' => $this->totalProductAmount
        ]);
    }
}
