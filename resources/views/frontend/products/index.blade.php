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
                            <h3>Products
                                <a href="{{ url('products/create') }}" class="btn btn-primary btn-sm text-white float-end">
                                    Add Products
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderd table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>User Owner Product</th>
                                        <th>Product</th>
                                        <th>Prices</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->category)
                                                {{ $product->category->name }}
                                            @else
                                                No Category
                                            @endif
                                        </td>
                                        <td>{{ $product->productUser->name }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>₱{{ $product->selling_price }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        @if( $product->status == '1')
                                            <td style="color:red">{{ $product->status == '1' ? 'Not Verified ':'Verified '}}</td>
                                        @else
                                        <td style="color:green">{{ $product->status == '1' ? 'Not Verified ':'Verified '}}</td>
                                        @endif
                                        <td>
                                            <a href="{{ url('products/'.$product->id.'/edit') }}" class="btn btn-sm" style="color:white; background-color:darkblue">Edit</a>
                                            <a href="{{ url('products/'.$product->id.'/delete') }}" onclick="return confirm('Are you sure, you want to delete this data?')" class="btn btn-sm btn-danger">
                                                Delete
                                            </a>
                                        </td>
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
