<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
class WishlistShow extends Component
{
    public function removeWishlistItem(int $wishlistId)
    {
        $wishId = Wishlist::where('user_id', auth()->user()->id)->where('id', $wishlistId)->value('product_id');
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' remove Product id:'. $wishId . ' from the Wishlist';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        Wishlist::where('user_id', auth()->user()->id)->where('id', $wishlistId)->delete();
        $this->emit('wishlistAddedUpdated');
        $this->dispatchBrowserEvent('message', [
            'text' => 'Wishtlist Item Remove Successfully',
            'type' => 'success',
            'status' => 200
        ]);
    }

    public function render()
    {
        $wishlist = Wishlist::where('user_id', auth()->user()->id)->get();
        
        return view('livewire.frontend.wishlist-show',[
            'wishlist' => $wishlist
        ]);
    }


}
