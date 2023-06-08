@extends('layouts.app')

@section('title', 'Recent Posted Item')

@section('content')
<div style=" background-color: rgba(199, 255, 160, 0.5);">

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Recent Posted Item</h4>
                    <div class="underline mb-4"></div>
                </div>
                @forelse ($newArrivalProducts as $productsItem)
                    <div class="col-md-3">

                        <div class="product-card">
                            <div class="product-card-img">
                                <label class="stock bg-success">New</label>


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
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <h4>No Products Available</h4>
                    </div>
                @endforelse

                <div class="text-center">
                    <a href="{{ url('/collections') }}" class="btn custom-bg px-3">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
