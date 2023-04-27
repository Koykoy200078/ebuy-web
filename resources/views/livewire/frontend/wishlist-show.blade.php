<div>
    <div class="py-3 py-md-5 bg-light">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="shopping-cart" >
                        <div class="mb-50">
                            <h1 class="heading-2 mb-10">Your Wishlist</h1>
                            <h6 class="text-body">There are <span class="text-brand"><livewire:frontend.wishlist-count/></span> products in this list</h6>
                            <br>
                            <br>
                        </div>
                        <div class="cart-header d-none d-sm-none d-mb-block d-lg-block" style="background-color: rgb(233, 233, 233); border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><h3>Product</h3></strong>
                                </div>
                                <div class="col-md-2">
                                    <strong><h3>Price</h3></strong>
                                </div>

                                <div class="col-md-4">
                                    <strong><h3>Remove</h3></strong>
                                </div>
                            </div>
                        </div>
                       
                        @forelse ($wishlist as $wishlistItem)
                        @if ($wishlistItem->product)
                        <div class="cart-item">
                            <div class="row">
                                <div class="col-md-6 my-auto">
                                    <a href="{{ url('collections/'.$wishlistItem->product->category->slug.'/'.$wishlistItem->product->slug) }}">
                                        <label class="product-name">
                                            <img src="{{ $wishlistItem->product->productImages[0]->image }}"
                                            style="width: 100px; height: 100px"
                                            alt="{{ $wishlistItem->product->name }}"/>
                                            &nbsp;&nbsp;&nbsp;<span style="color: rgb(0, 0, 0);">{{ $wishlistItem->product->name }} </span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-md-2 my-auto">
                                    <label class="price">₱{{ $wishlistItem->product->selling_price }}</label>
                                </div>

                                <div class="col-md-4 col-12 my-auto">
                                    <div class="remove">
                                        <button type="button" wire:click="removeWishlistItem({{ $wishlistItem->id }})" class="btn btn-danger btn-sm">
                                            <span wire:loading.remove wire:target="removeWishlistItem({{ $wishlistItem->id }})">
                                                <i class="fa fa-trash"></i> Remove
                                            </span>
                                            <span wire:loading wire:target="removeWishlistItem({{ $wishlistItem->id }})">
                                                <i class="fa fa-trash"></i> Removing
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                            <h4>No Wishlist Added</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    
</div>
