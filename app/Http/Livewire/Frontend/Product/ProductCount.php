<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProductCount extends Component
{
    public $productCount;

    protected $listeners = ['ProductAddedUpdated' => 'checkProductCount'];

    public function checkProductCount()
    {
        if(Auth::check())
        {
            return $this->productCount = Product::where('product_user_id', auth()->user()->id)->count();
        }
        else{
            return $this->productCount = 0;
        }
    }

    public function render()
    {

        $this->productCount = $this->checkProductCount();
        return view('livewire.frontend.product.product-count', [
            'productCount' => $this->productCount
        ]);
    }
}
