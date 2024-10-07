@extends('user.master')

@section('title', 'Dashboard')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card text-center bg-success text-white py-4">
                    <div class="task-label">Completed Tasks</div>
                    <div class="task-count">{{ $completedTasks }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card text-center bg-warning text-white py-4">
                    <div class="task-label">Pending Tasks</div>
                    <div class="task-count">{{ $pendingTasks }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection