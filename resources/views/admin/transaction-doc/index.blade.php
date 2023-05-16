@extends('layouts.admin')

@section('title', 'Transaction Document')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if (session('message'))
                <div class="alert alert-success">{{(session('message'))}}</div>
                @endif
                <div class="card-header">
                    <h3>Transaction Document
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ url('admin/save-transaction/create7') }}" class="btn btn-primary btn-sm text-white float-end">
                        Add Transaction Document
                    </a>
                    <br>
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Tracking No</th>
                                    <th>Transaction ID</th>
                                    <th>Payment Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @forelse ($transactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->tracking_number }}</td>
                                            <td>{{ $item->payment_id }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                            <td>
                                            <a href="{{ url('admin/save-transaction/'.$item->id.'/edit') }}"  class="btn btn-success btn-sm">Edit</a>
                                            <a href="{{ url('admin/save-transaction/'.$item->id.'/delete') }}" onclick="return confirm('Are you sure you want to delete this data?')" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                        @php
                                            $counter++;
                                        @endphp
                                @empty
                                    <tr>
                                        <td colspan="7">No Transaction Document available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
