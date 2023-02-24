<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistCount extends Component
{
    public $wishlistCount;

    protected $listeners = ['wishlistAddedUpdated' => 'checkWistlistCount'];
    public function checkWistlistCount()
    {
        if(Auth::check()){
            return $this->wishlistCount= Wishlist::where('user_id',auth()->user()->id)->count();
        }else{
            return $this->wishlistCount = 0;
        }
    }

    public function render()
    {
        $this->wishlistCount = $this->checkWistlistCount();
        return view('livewire.frontend.wishlist-count', [
            'wishlistCount' => $this->wishlistCount
        ]);
    }
}
