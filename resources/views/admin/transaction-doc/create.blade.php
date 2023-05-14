@extends('layouts.admin')

@section('title', 'Transaction Document')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if (session('message'))
                <div class="alert alert-danger">{{(session('message'))}}</div>
                @endif
                <div class="card-header">
                    <h3>Transaction Document
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ url('admin/save-transaction') }}" class="btn btn-primary btn-sm float-end">
                        <span class="fa fa-arrow-left"></span> Back
                    </a>
                    <br>
                    <hr>

                    <div class="card-body">
                        <form action="{{ url('admin/save-transaction') }}" method="POST" enctype="multipart/form-data">
                            @csrf
        
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Traking Number</label>
                                    <input type="text" name="tracking_number" class="form-control" />
                                    @error('tracking_number') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-4"> </div>
                                <div class="col-md-6 mb-4"> </div>
                               
                                <div class="col-md-6 mb-3 offset-md-3 text-center">
                                    <label>Payment Id</label>
                                    <input type="text" name="payment_id" class="form-control" />
                                    @error('payment_id') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Image</label>
                                    <input type="file" name="image_pdf" class="form-control"/>
                                    @error('image_pdf') <small class="text-danger">{{$message}}</small> @enderror
        
                                </div>
                          
        
                                <div class="col-md-12 mb-3">
                                    <button type="submit" class="btn btn-primary float-end">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
