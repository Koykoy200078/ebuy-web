<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class View extends Component
{

    public $category, $product, $prodColorSelectedQuantity, $quantityCount = 1, $productColorId;


    public function addToWishList($productId)
    {
        $verify = Product::where('product_user_id', auth()->user()->id)->value('product_user_id');
        $Productverify = Product::where('id', $productId)->value('product_user_id');

        if ($Productverify != $verify) {
            if (Auth::check()) {
                if (Wishlist::where('user_id', auth()->user()->id)->where('product_id', $productId)->exists()) {
                    session()->flash('message', 'Already added to wish list');
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Already added to wish list',
                        'type' => 'warning',
                        'status' => 409
                    ]);
                    return false;
                } else {
                    Wishlist::create([
                        'user_id' => auth()->user()->id,
                        'product_id' => $productId,
                    ]);
                    $this->emit('wishlistAddedUpdated');
                    session()->flash('message', 'Wishlist added successfully');
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Wishlist added successfully',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            } else {
                session()->flash('message', 'Please Login to Continue');
                $this->dispatchBrowserEvent('message', [
                    'text' => 'Please Login to Continue',
                    'type' => 'info',
                    'status' => 401
                ]);
                return false;
            }
        } else {
            session()->flash('message', 'You cannot buy your own product');
            $this->dispatchBrowserEvent('message', [
                'text' => 'You cannot buy your own product',
                'type' => 'info',
                'status' => 401
            ]);
            return false;
        }
    }

    public function incrementQuantitys()
    {
        if ($this->quantityCount < 10) {
            $this->quantityCount++;
        }
    }

    public function decrementQuantitys()
    {
        if ($this->quantityCount > 1) {
            $this->quantityCount--;
        }
    }

    public function colorSelected($productColorId)
    {
        $this->productColorId = $productColorId;
        $productColor = $this->product->productColors()->where('id', $productColorId)->first();
        $this->prodColorSelectedQuantity = $productColor->quantity;

        if ($this->prodColorSelectedQuantity == 0) {
            $this->prodColorSelectedQuantity = 'outOfStock';
        }
    }

    public function addToCart(int $productId)
    {
        $verify = Product::where('product_user_id', auth()->user()->id)->value('product_user_id');
        $Productverify = Product::where('id', $productId)->value('product_user_id');

        if ($Productverify != $verify) {
            if (Auth::check()) {
                if ($this->product->where('id', $productId)->where('status', '0')->exists()) {
                    // Check for product color quantity and add to cart
                    if ($this->product->productColors()->count() > 1) {
                        if ($this->prodColorSelectedQuantity != null) {
                            if (Cart::where('user_id', auth()->user()->id)
                                ->where('product_id', $productId)
                                ->where('product_color_id', $this->productColorId)
                                ->exists()
                            ) {
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Product Already Added',
                                    'type' => 'warning',
                                    'status' => 200
                                ]);
                            } else {
                                $productColor = $this->product->productColors()->where('id', $this->productColorId)->first();
                                // dd($productColor);
                                if ($productColor->quantity > 0) {
                                    if ($productColor->quantity >= $this->quantityCount) {
                                        // Insert Product to Cart
                                        Cart::create([
                                            'user_id' => auth()->user()->id,
                                            'product_id' => $productId,
                                            'product_color_id' => $this->productColorId,
                                            'quantity' => $this->quantityCount

                                        ]);

                                        $this->emit('CartAddedUpdated');
                                        $this->dispatchBrowserEvent('message', [
                                            'text' => 'Product Added to Cart',
                                            'type' => 'success',
                                            'status' => 200
                                        ]);
                                    } else {
                                        $this->dispatchBrowserEvent('message', [
                                            'text' => 'Only ' . $productColor->quantity . ' Quantity Available',
                                            'type' => 'warning',
                                            'status' => 404
                                        ]);
                                    }
                                } else {
                                    $this->dispatchBrowserEvent('message', [
                                        'text' => 'Out of Stock',
                                        'type' => 'warning',
                                        'status' => 404
                                    ]);
                                }
                            }
                        } else {
                            $this->dispatchBrowserEvent('message', [
                                'text' => 'Select Your Product Color',
                                'type' => 'info',
                                'status' => 404
                            ]);
                        }
                    } else {
                        if (Cart::where('user_id', auth()->user()->id)->where('product_id', $productId)->exists()) {
                            $this->dispatchBrowserEvent('message', [
                                'text' => 'Product Already Added',
                                'type' => 'warning',
                                'status' => 200
                            ]);
                        } else {
                            if ($this->product->quantity > 0) {
                                if ($this->product->quantity >= $this->quantityCount) {
                                    // Insert Product to Cart
                                    Cart::create([
                                        'user_id' => auth()->user()->id,
                                        'product_id' => $productId,
                                        'quantity' => $this->quantityCount

                                    ]);

                                    $this->emit('CartAddedUpdated');
                                    $this->dispatchBrowserEvent('message', [
                                        'text' => 'Product Added to Cart',
                                        'type' => 'success',
                                        'status' => 200
                                    ]);
                                } else {
                                    $this->dispatchBrowserEvent('message', [
                                        'text' => 'Only ' . $this->product->quantity . ' Quantity Available',
                                        'type' => 'warning',
                                        'status' => 404
                                    ]);
                                }
                            } else {
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Out of Stock',
                                    'type' => 'warning',
                                    'status' => 404
                                ]);
                            }
                        }
                    }
                } else {
                    $this->dispatchBrowserEvent('message', [
                        'text' => 'Product does not exist',
                        'type' => 'warning',
                        'status' => 404
                    ]);
                }
            } else {
                $this->dispatchBrowserEvent('message', [
                    'text' => 'Please Login to add to cart',
                    'type' => 'info',
                    'status' => 401
                ]);
            }
        } else {
            session()->flash('message', 'You cannot buy your own product');
            $this->dispatchBrowserEvent('message', [
                'text' => 'You cannot buy your own product',
                'type' => 'info',
                'status' => 401
            ]);
            return false;
        }
    }

    public function mount($category, $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.frontend.product.view', [
            'product' => $this->product,
            'category' => $this->category,
        ]);
    }
}
