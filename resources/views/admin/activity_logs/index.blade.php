@extends('layouts.admin')

@section('title', 'My Activity Log')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>My Activity Log
                </h3>
            </div>
            <div class="card-body">


                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Description</th>
                                <th>Date and Time</th>

                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($activityLogs as $activityLogs)
                            <tr>
                                <td>{{ $activityLogs->user ? $activityLogs->user->name : 'N/A' }}</td>
                                <td>{{ $activityLogs->description }}</td>
                                <td>{{ $activityLogs->created_at }}</td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="7">No More Activity log</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div>
                        {{-- {{ $orders->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection