@extends('layouts.app')

@section('title', 'Search Products')

@section('content')


    <div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h4>Search Result</h4>
                <div class="underline mb-4"></div>
            </div>
            @forelse ($searchProducts as $productsItem)
            <div class="col-md-10">
                <div class="product-card">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="product-card-img">
                                <label class="stock bg-danger">New</label>
                                @if ($productsItem->productImages->count() > 0)
                                <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                    <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}" width="500" height="300">
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
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
                                    <span > &nbsp;( {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity ?? '0' }} ) </span>


                                </div>
                                <p style="height: 45px; overflow: hidden">
                                   <b>Description: </b> {{ $productsItem->description }}
                                </p>
                                <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}"
                                    class="btn btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-md-12">
                    <h4>No Search Products Found</h4>
                </div>
            @endforelse

            <div class="col-md-10">
                {{ $searchProducts->appends(request()->input())->links() }}
            </div>

        </div>
    </div>
  </div>


@endsection
