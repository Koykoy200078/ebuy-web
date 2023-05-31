@extends('layouts.app')

@section('title', 'Thank You for Shopping')

@section('content')

<div class="py-3 py-md-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"> @if (session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif <div class="p-4 shadow bg-white">
                    <h2>Thank you for shopping with {{ $appSetting->website_name ?? 'website name'}}, your local eCommerce platform.</h2>
                    <h6>Your order has been successfully placed. Please click on the button below to access your order details in "My Orders".</h6> <a href="{{ url('/orders')}}" class="btn btn-primary" role="button">My Orders</a> <a href="{{ url('collections') }}" class="btn btn-success" role="button">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div> @endsection