@extends('layouts.app')

@section('content')

<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
                <div class="col-md-12">
                    @if (session('message'))
                            <div class="alert alert-success">{{(session('message'))}}</div>
                        @endif
                    <div class="card">
                        <div class="card-header">
                            <h3>Orders
                               
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderd table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Product</th>
                                        {{-- <th>Product Color</th> --}}
                                        <th>Prices</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productCheck as $productcheking)
                                    <tr>
                                        <td>{{ $productcheking->product->name }}</td>
                                        {{-- @if($productcheking->productColor->color->name != null)
                                            <td>{{ $productcheking->productColor->color->name}}</td>
                                        @else 
                                            <td>No Colors</td>
                                        
                                        @endif --}}
                                        <td>₱{{ $productcheking->price }}</td>
                                        <td>{{ $productcheking->quantity }}</td>
                                        <td>{{ $productcheking->status_message }}</td>
                                        {{-- <td>{{ $productcheking->productUser->name }}</td> --}}
                                        {{-- <td>{{ $productcheking->name }}</td>
                                        <td>{{ $productcheking->selling_price }}</td>
                                        <td>{{ $productcheking->quantity }}</td> --}}
                                        {{-- @if( $product->status == '1')
                                            <td style="color:red">{{ $product->status == '1' ? 'Not Verified ':'Verified '}}</td>
                                        @else --}}
                                        {{-- <td style="color:green">{{ $product->status == '1' ? 'Not Verified ':'Verified '}}</td>
                                        @endif --}}
                                       
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No Products Available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>



@endsection
