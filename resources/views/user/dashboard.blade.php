@extends('user.master')

@section('title', 'Dashboard')

@section('content')
<div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="dashboard-card text-center">
                
                        <div class="task-label">Total Tasks</div>
                        <div>{{$name}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection