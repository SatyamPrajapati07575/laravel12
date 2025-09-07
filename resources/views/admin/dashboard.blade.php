@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-users"></i> Manage Users</a>
    <a href="#"><i class="fas fa-box"></i> Manage Products</a>
    <a href="#"><i class="fas fa-cogs"></i> Settings</a>
@endsection

@section('page_title', 'Welcome Admin, ' . Auth::user()->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x text-info"></i>
                <h5 class="mt-3">Users</h5>
                <p>View and manage registered users.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x text-warning"></i>
                <h5 class="mt-3">Reports</h5>
                <p>Check application statistics.</p>
            </div>
        </div>
    </div>
</div>
@endsection
