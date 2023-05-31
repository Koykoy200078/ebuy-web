@extends('layouts.app')

@section('title', 'Thank You for Shopping')

@section('content')

    <div class="py-3 pyt-md-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    @if (session('message'))
                        <h5 class="alert alert-success">{{ session('message') }}</h5>
                    @endif

                    <div class="p-4 shadow bg-white">
                        <h2>Thank You for Shopping with {{ $appSetting->website_name ?? 'website name'}}</h2>
                        <h4>Click here to go to your 
                            <a href="{{ url('/orders')}}" style="color: inherit; text-decoration: none;">My Order</a>

                        </h4>
                        <a href="{{ url('collections') }}" class="btn btn-success">SHOP NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
