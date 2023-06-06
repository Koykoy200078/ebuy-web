@extends('layouts.app')

@section('title', 'My Sales')

@section('content')

    <div class="py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 class="mb-4">My Sales</h4>
                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-body bg-primary text-white mb-3">
                                    <label for="">Total Sales</label>
                                    <h1>₱ {{$totalPrice}}</h1>
                                    {{-- <a href="{{ url('admin/orders') }}" class="text-white">view</a> --}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-success text-white mb-3">
                                    <label for="">Today Sales</label>
                                    <h1>₱ {{$totalPriceToday}}</h1>
                                    {{-- <a href="{{ url('admin/orders') }}" class="text-white">view</a> --}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-warning text-white mb-3">
                                    <label for="">This Month Sales</label>
                                    <h1>₱ {{$totalPriceMonth}}</h1>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-danger text-white mb-3">
                                    <label for="">Year Sales</label>
                                    <h1>₱ {{$totalPriceYear}}</h1>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
