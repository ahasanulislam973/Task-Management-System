@extends('admin.master')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Highest Task Solver</div>
            <div class="card-body">
                <h5 class="card-title">{{ $highestSolver->name }}</h5>
                <p class="card-text">
                    {{ $highestSolver->tasks_count }} tasks completed.
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Solving Time</div>
            <div class="card-body">
                <table class="table table-borderless text-white">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Solving Time (H:M:S)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solvingTimes as $time)
                        <tr>
                            <td>{{ $time->assignedUser->name }}</td>
                            <td>{{ $time->total_time_formatted }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection