@extends('layouts.admin')

@section('title', 'Transaction Document')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if(session('message'))
                <div class="alert alert-success mb-3">{{ session('message') }}</div>
            @endif

                <div class="card-header">
                    <h3>Transaction Document
                    </h3>
                </div>

                <div class="card-body">
               
                 
                    <a href="{{ url('admin/save-transaction') }}" class="btn btn-danger btn-sm float-end">
                        <span class="fa fa-arrow-left"></span> Back
                    </a>
                    {{-- <a href="{{ url('admin/save-transaction/'.$transaction->id.'/generate') }}" class="btn btn-primary btn-sm float-end mx-1">
                        <span class="fa fa-download"></span> Download Invoice
                    </a> --}}
                    <a href="{{ url('admin/save-transaction/'.$transaction->id) }}" target="_blank" class="btn btn-warning btn-sm float-end mx-1">
                        <span class="fa fa-eye"></span> View Invoice
                    </a>
                    <a href="{{ url('admin/save-transaction/'.$transaction->id.'/mail') }}" class="btn btn-info btn-sm float-end mx-1">
                        <span class="fa fa-eye"></span> Send Invoice Via Mail
                    </a>
                    <br>
                    
                    <hr>

                    <div class="card-body">
                        <form action="{{ url('admin/save-transaction/'.$transaction->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
        
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Traking Number</label>
                                    <input type="text" name="tracking_number" value="{{ $transaction->tracking_number }}" class="form-control" readonly/>
                                    @error('tracking_number') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-4"> </div>
                                <div class="col-md-6 mb-4"> </div>
                               
                                <div class="col-md-6 mb-3 offset-md-3 text-center">
                                    <label>Payment Id</label>
                                    <input type="text" name="payment_id" value="{{ $transaction->payment_id }}" class="form-control" readonly/>
                                    @error('payment_id') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                                

                                <div class="col-md-12 mb-3">
                                   
                                    <label>Image</label>
                                    <input type="file" name="image_pdf" value="{{ $transaction->image }}" class="form-control" />
                                    <img src="{{ asset($transaction->image_pdf) }}" width="60px" height="60px"/>
                                    @error('image_pdf') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                          
        
                                <div class="col-md-12 mb-3">
                                    <button type="submit" class="btn btn-primary float-end">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
