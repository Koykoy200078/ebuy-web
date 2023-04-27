@extends('layouts.app')

@section('title', 'Features Product')

@section('content')

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Features Products</h4>
                <div class="underline mb-4"></div>
            </div>
            @forelse ($featuredProducts as $productsItem)
                <div class="col-md-3">

                    <div class="product-card">
                        <div class="product-card-img">
                            <label class="stock" style="background-color: #FFD700">Featured</label>



                            @if ($productsItem->productImages->count() > 0)
                            <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}" width="500" height="300">
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
                                <span class="selling-price">₱{{ $productsItem->selling_price }}</span>
                                <span class="original-price">₱{{ $productsItem->original_price }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <h4>No Featured Products Available</h4>
                </div>
            @endforelse

            <div class="text-center">
                <a href="{{ url('/collections') }}" class="btn custom-bg px-3">Shop More</a>
            </div>
        </div>
    </div>
  </div>

@endsection
