<div>
    
    <div class="py-3 py-md-5">
        <div class="container">
            <div class="mb-50">
                <h1 class="heading-2 mb-10">Your Cart</h1>
                <h6 class="text-body">There are <span class="text-brand"><livewire:frontend.cart.cart-count/></span> products in this list</h6>
            </div>
            <hr>
            <br>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="shopping-cart">
                       
                        <div class="cart-header d-none d-sm-none d-mb-block d-lg-block" style="background-color: rgb(233, 233, 233); border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Products</h4>
                                </div>
                                <div class="col-md-1">
                                    <h4>Price</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4>Quantity</h4>
                                </div>
                                <div class="col-md-1">
                                    <h4>Total</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4>Remove</h4>
                                </div>
                            </div>
                        </div>
                        @forelse ($cart as $cartItem)
                            @if ($cartItem->product)
                            <div class="cart-item">
                                <div class="row">
                                    <div class="col-md-6 my-auto">
                                        <a href="{{ url('collections/'.$cartItem->product->category->slug.'/'.$cartItem->product->slug) }}">
                                            <label class="product-name">

                                                @if ($cartItem->product->productImages)
                                                    <img src="{{ asset($cartItem->product->productImages[0]->image) }}"
                                                    style="width: 100px; height: 100px" alt="">
                                                @else
                                                    <img src="" style="width: 100px; height: 000px" alt="">
                                                @endif

                                                &nbsp;&nbsp;&nbsp;{{ $cartItem->product->name }}

                                                @if ($cartItem->productColor)
                                                    @if ($cartItem->productColor->color)
                                                    <span>- Color: {{ $cartItem->productColor->color->name }}</span>
                                                    @endif
                                                @endif

                                            </label>
                                        </a>
                                    </div>
                                    <div class="col-md-1 my-auto">
                                        <label class="price">₱{{ $cartItem->product->selling_price }} </label>
                                    </div>
                                    <div class="col-md-2 col-7 my-auto">
                                        <div class="quantity">
                                            <div class="input-group">
                                                <button type="button" wire:loading.attr="disabled" wire:click="decrementQuantity({{ $cartItem->id }})" class="btn btn1"><i class="fa fa-minus"></i></button>
                                                <input type="text" value="{{ $cartItem->quantity }}" class="input-quantity" />
                                                <button type="button" wire:loading.attr="disabled" wire:click="incrementQuantity({{ $cartItem->id }})" class="btn btn1"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 my-auto">
                                        <label class="price">₱{{ $cartItem->product->selling_price * $cartItem->quantity }} </label>
                                        @php $totalPrice += $cartItem->product->selling_price * $cartItem->quantity @endphp
                                    </div>
                                    <div class="col-md-2 col-5 my-auto">
                                        <div class="remove">
                                            <button type="button" wire:loading.attr="disabled" wire:click="removeCartItem({{ $cartItem->id }})" class="btn btn-danger btn-sm">
                                                <span wire:loading.remove wire:target="removeCartItem({{ $cartItem->id }})">
                                                    <i class="fa fa-trash"></i> Remove

                                                </span>
                                                <span wire:loading wire:target="removeCartItem({{ $cartItem->id }})">
                                                    <i class="fa fa-trash"></i> Removing

                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        @empty
                            <div>No Cart Items available</div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 my-md-auto mt-3">
                    <h4>
                        Get the best deals & Offers <a href="{{ url('/collections') }}">shop now</a>
                    </h4>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="shadow-sm bg-white p-3">
                        <h4>Total:
                            <span class="float-end">₱ {{ $totalPrice }}</span>
                        </h4>
                        <hr>
                        <a href="{{ url('/checkout') }}" class="btn btn-warning w-100">Checkout</a>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@section('script')

<script>
   

</script>

@endsection

