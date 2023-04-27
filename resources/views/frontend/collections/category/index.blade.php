@extends('layouts.app')

@section('title', 'All Categories')

@section('content')

<div class="py-3 py-md-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-4">Our Categories</h4>
            </div>

           @forelse ($categories as $categoryItem)


            <div class="col-6 col-md-3">
                <div class="category-card">
                    <a href="{{ url('/collections/'.$categoryItem->slug) }}">
                        <div class="category-card-img">
                            <img src="{{ asset("$categoryItem->image") }}" class="w-100" alt="Laptop" width="500" height="300">
                        </div>
                        <div class="category-card-body">
                            <h5>{{$categoryItem->name}}</h5>
                        </div>
                    </a>
                </div>
            </div>
            @empty
                <div class="col-md-12">
                    <h5>No Category Avaiable</h5>
                </div>

            @endforelse
            {{-- <div class="col-6 col-md-3">
                <div class="category-card">
                    <a href="">
                        <div class="category-card-img">
                            <img src="mobile.jpg" class="w-100" alt="Mobile Devices">
                        </div>
                        <div class="category-card-body">
                            <h5>Mobile</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="category-card">
                    <a href="">
                        <div class="category-card-img">
                            <img src="mens-fashion.jpg" class="w-100" alt="Mens Fashion">
                        </div>
                        <div class="category-card-body">
                            <h5>Mens Fashion</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="category-card">
                    <a href="">
                        <div class="category-card-img">
                            <img src="women.jpg" class="w-100" alt="Women Fashion">
                        </div>
                        <div class="category-card-body">
                            <h5>Women Fashion</h5>
                        </div>
                    </a>
                </div>
            </div> --}}
        </div>
    </div>
</div>

@endsection
