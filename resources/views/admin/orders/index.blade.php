@extends('layouts.admin')

@section('title', 'My Orders')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>My Orders
                    </h3>
                </div>
                <div class="card-body">

                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Filter by Date</label>
                                <br>
                                Start Date:<input type="date" name="date" value="{{ Request::get('date') ?? date('Y-m-d') }}" class="form-control">
                            </div>
                    
                            <div class="col-md-3">
                                <br>
                                Current Date:<input type="date" id="end-date" name="date2" value="{{ Request::get('date2') ?? date('Y-m-d', strtotime('+5 days')) }}" class="form-control" min="{{ date('Y-m-d', strtotime('+5 days')) }}"readonly>
                            </div>
                            <div class="col-md-3">
                                <br>
                                <label>Filter by Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Select All Status</option>
                                    <option value="completed" {{ Request::get('status') == 'completed' ? 'selected':'' }}>Approved</option>
                                                <option value="cancelled" {{ Request::get('status') == 'cancelled' ? 'selected':'' }}>Cancelled</option>
                                                <option value="in progress" {{ Request::get('status') == 'in progress' ? 'selected':'' }}>In Progress</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <br>
                                <label>Search Tracking No.</label>
                                <input type="text" name="search" value="{{ Request::get('search') }}" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <br>
                                <br>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                    
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tracking No</th>
                                    <th>Username</th>
                                    <th>Payment Mode</th>
                                    <th>Order Date</th>
                                    <th>Status Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @forelse ($orders as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->tracking_no }}</td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->payment_mode }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $item->status_message }}</td>
                                            <td><a href="{{ url('admin/orders/'.$item->id) }}" class="btn btn-primary btn-sm">View</a></td>
                                        </tr>
                                        @php
                                            $counter++;
                                        @endphp
                                @empty
                                    <tr>
                                        <td colspan="7">No Orders available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
