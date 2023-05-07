<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;

// use Illuminate\Support\Facades\Auth;

class CartShow extends Component
{
    public $selectedIds = [];
    public $cart, $totalPrice = 0;
    protected $listeners = ['checkBox'];
    public function decrementQuantity(int $cartId)
    {
        $cartData = Cart::where('id', $cartId)->where('user_id', auth()->user()->id)->first();
        if($cartData)
        {
            $cartData = Cart::where('id',$cartId)->where('user_id', auth()->user()->id)->first();
            if($cartData)
            {
                if($cartData->quantity >= 1){

                    $cartData->decrement('quantity');
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }else{
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Quantity cannot be less than 1',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }else{

                $this->dispatchBrowserEvent('message', [
                    'text' => 'Something Went Wrong!',
                    'type' => 'error',
                    'status' => 404
                ]);
    }

        }
        else
        {
            $this->dispatchBrowserEvent('message', [
                'text' => 'Something went wrong!',
                'type' => 'error',
                'status' => 404
            ]);
        }
    }

    public function removeCartItem(int $cartId)
    {
        $cartRemoveData = Cart::where('user_id', auth()->user()->id)->where('id', $cartId)->first();
        if($cartRemoveData)
        {
            $cartRemoveData->delete();
            $this->emit('CartAddedUpdated');
            $this->dispatchBrowserEvent('message', [
                'text' => 'Cart Item Removed Successfuly',
                'type' => 'success',
                'status' => 200
            ]);
        }
        else{
            $this->dispatchBrowserEvent('message', [
                'text' => 'Something went wrong!',
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function incrementQuantity(int $cartId)
    {
        $cartData = Cart::where('id', $cartId)->where('user_id', auth()->user()->id)->first();
        if($cartData)
        {
            if($cartData->productColor()->where('id', $cartData->product_color_id)->exists())
            {
                //Color Product Quantity

                $productColor = $cartData->productColor()->where('id', $cartData->product_color_id)->first();
                if($productColor->quantity > $cartData->quantity)
                {
                    $cartData->increment('quantity');
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Only '.$productColor->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }
            else
            {
                //Normal Product Quantity
                if($cartData->product->quantity > $cartData->quantity)
                {
                    $cartData->increment('quantity');
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Only '.$cartData->product->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }


        }
        else
        {
            $this->dispatchBrowserEvent('message', [
                'text' => 'Something went wrong!',
                'type' => 'error',
                'status' => 404
            ]);
        }
    }
        
    public function checkBox($selectedIds)
    {
        // Update the selectedIds array with the new values
        $this->selectedIds = $selectedIds;
        
        // Recalculate the total price based on the selected items
        $this->totalPrice = 0;
        foreach ($this->cart as $cartItem) {
            if (in_array($cartItem->id, $this->selectedIds)) {
                $this->totalPrice += $cartItem->product->selling_price * $cartItem->quantity;
            }
        }
    }
    
    
    
    public function render()
    {
        $this->cart = Cart::where('user_id', auth()->user()->id)
        ->orderBy('product_user_id', 'asc')
        ->get();
        $totalPrice = 0;
        foreach ($this->cart as $cartItem) {
        if (in_array($cartItem->id, $this->selectedIds)) {
            $totalPrice += $cartItem->product->selling_price * $cartItem->quantity;
            }
        }
        $totalPrice = 0;
        return view('livewire.frontend.cart.cart-show', [
            'cart' => $this->cart,
            'totalPrice' => $totalPrice
        ]);

        
    }
    // public function render()
    // {
    //     $this->cart = Cart::where('user_id', auth()->user()->id)->get();
    //     return view('livewire.frontend.cart.cart-show', [
    //         'cart' => $this->cart
    //     ]);
    // }

}
