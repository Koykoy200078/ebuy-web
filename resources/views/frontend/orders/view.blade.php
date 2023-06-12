@extends('layouts.app')

@section('title', 'My Order Details')

@section('content')

    <div class="py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    @if(session('message'))
                        <div class="alert alert-success mb-3">{{ session('message') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3>My Orders Details
                                {{-- <i class="fa fa-shopping-cart text-dark"></i> My Order Details --}}
                                <a href="{{ url('orders') }}" class="btn btn-danger btn-sm float-end">
                                    <span class="fa fa-arrow-left"></span> Back
                                </a>
                                <a href="{{ url('invoice/'.$order->id.'/generate') }}" class="btn btn-primary btn-sm float-end mx-1">
                                    <span class="fa fa-download"></span> Download Invoice
                                </a>
                                <a href="{{ url('invoice/'.$order->id) }}" target="_blank" class="btn btn-warning btn-sm float-end mx-1">
                                    <span class="fa fa-eye"></span> View Invoice
                                </a>
                                {{-- <a href="{{ url('invoice/'.$order->id.'/mail') }}" class="btn btn-info btn-sm float-end mx-1">
                                    <span class="fa fa-eye"></span> Send Invoice Via Mail
                                </a> --}}
                            </h3>
                        </div>
                        <div class="card-body">

                            {{-- <h4 class="text-primary">

                            </h4>
                            <hr> --}}

                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Order Details</h5>
                                    <hr>
                                    {{-- <h6>Order Id: {{ $order->id }}</h6> --}}
                                    <h6>Tracking Id/No.: {{ $order->tracking_no }}</h6>
                                    <h6>Order Date: {{ $order->created_at->format('M-d-Y h:i A') }}</h6>
                                    <h6>Payment Mode: {{ $order->payment_mode }}</h6>
                                    <h6>Payment Id: {{$order->payment_id ?? 'N/A'}}</h6>

                                    <h6 class="border p-2 text-success">
                                        Order Status Message:
                                        <span class="text-uppercase">{{ $order->status_message }}</span>
                                    </h6>
                                    <h6 class="border p-2 text-success">
                                        Delivery status:
                                        <span class="text-uppercase">{{ $order->confirm }}</span>
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <h5>User Details</h5>
                                    <hr>
                                    <h6>Full Name: {{ $order->fullname }}</h6>
                                    <h6>Email Address: {{ $order->email }}</h6>
                                    <h6>Phone: {{ $order->phone }}</h6>
                                    <h6>Address: {{ $order->address }}</h6>
                                    <h6>Pin code: {{ $order->pincode }}</h6>
                                    
                                </div>
                                <div class="col-md-4">
                                    <h5>Seller Details</h5>
                                    <hr>
                                    <h6>Full Name: {{ $order->seller_fullname }}</h6>
                                    <h6>Email Address: {{ $order->seller_email }}</h6>
                                    <h6>Phone: {{ $order->seller_phone ?? 'N/A'}}</h6>
                                
                                </div>
                            </div>

                            <br/>
                            <h5>Order Items</h5>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item ID</th>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                            $counter = 1;
                                        @endphp
                                        
                                        @foreach ($order->orderItems as $orderItem)
                                        <tr>
                                            <td width="10%">{{ $counter }}</td>
                                            <td width="10%">
                                                @if ($orderItem->product->productImages)
                                                    <img src="{{ asset($orderItem->product->productImages[0]->image) }}"
                                                    style="width: 50px; height: 50px" alt="">
                                                @else
                                                    <img src="" style="width: 50px; height: 50px" alt="">
                                                @endif

                                                {{-- {{ $orderItem->product->name }}

                                                @if ($orderItem->productColor)
                                                    @if ($orderItem->productColor->color)
                                                    <span>- Color: {{ $orderItem->productColor->color->name }}</span>
                                                    @endif
                                                @endif --}}
                                            </td>
                                            <td>
                                                {{ $orderItem->product->name }}

                                                @if ($orderItem->productColor)
                                                    @if ($orderItem->productColor->color)
                                                    <span>- Color: {{ $orderItem->productColor->color->name }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td width="10%">₱ {{ number_format($orderItem->price, 2, '.', ',') }}</td>
                                            <td width="10%" class="text-center">{{ $orderItem->quantity }}</td>
                                            <td width="10%" class="fw-bold">₱ {{ number_format($orderItem->quantity * $orderItem->price, 2, '.', ',')}}</td>
                                            @php
                                                $totalPrice += $orderItem->quantity * $orderItem->price;
                                            @endphp
                                        </tr>
                                        @php
                                            $counter++;
                                        @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="fw-bold text-end">Total Amount:</td>
                                            <td colspan="1" class="fw-bold">₱ {{ number_format($totalPrice, 2, '.', ',') }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>



                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    
                                    @if ($order->status_message == "completed")
                                    <form action="{{ url('orders/'.$order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="input-group">
                                            <h5>Click here once you have received your package.-></h5><button type="submit" class="btn btn-primary text-white">Item Delivered</button>
                                        </div>
                                    </form>
                                    @endif


                                   
                                </div>
                                <div class="col-md-7">
                                    <br/>
                                    <h4 class="mt-3">Current Order Status: <span class="text-uppercase">{{ $order->status_message }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
