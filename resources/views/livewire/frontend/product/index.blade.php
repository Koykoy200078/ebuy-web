<div>
    
    <div class="row">
        <div class="col-md-3">
            @if ($category->brands)

            <div class="card">
                <div class="card-header"><h4>Material Category</h4></div>
                <div class="card-body">
               
                    <label class="d-block">
                        <input type="checkbox" wire:model="brandInputs" value="Recycle" /> Recycle
                    </label>
                    <label class="d-block">
                        <input type="checkbox" wire:model="brandInputs" value="New" /> New
                    </label>
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-header"><h4>Price</h4></div>
                <div class="card-body">
                    <label class="d-block">
                        <input type="radio" name="priceSort" wire:model="priceInput" value="high-to-low" /> High to Low
                    </label>
                    <label class="d-block">
                        <input type="radio" name="priceSort" wire:model="priceInput" value="low-to-high" /> Low to High
                    </label>
                </div>
            </div>

        </div>
        <div class="col-md-9">


            <div class="row">
                @forelse ($products as $productsItem)

                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-card-img">
                                @if ($productsItem->quantity > 0)
                                <label class="stock bg-success">In Stock</label>
                                @else
                                <label class="stock bg-danger">Out of Stock</label>

                                @endif

                                @if ($productsItem->productImages->count() > 0)
                                <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                    <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }} width="500" height="300">
                                </a>

                                @endif

                            </div>
                            <div class="product-card-body">
                                <p class="product-brand">{{ $productsItem->brand }}</p>
                                <h5 class="product-name">
                                <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                        {{ $productsItem->name }}
                                </a>
                                </h5>
                                <div>
                                    <span class="selling-price">₱{{  number_format($productsItem->selling_price, 2, '.', ',') }}</span>
                                    <span class="original-price">₱{{ number_format($productsItem->original_price, 2, '.', ',') }}</span>
                                    <span > &nbsp;( {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity ?? '0' }} ) </span>

                                </div>
                                {{-- <div class="mt-2">
                                    <a href="" class="btn btn1">Add To Cart</a>
                                    <a href="" class="btn btn1"> <i class="fa fa-heart"></i> </a>
                                    <a href="" class="btn btn1"> View </a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                        <div class="p-2">
                            <h4>No Products Available for {{ $category->name }}</h4>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
