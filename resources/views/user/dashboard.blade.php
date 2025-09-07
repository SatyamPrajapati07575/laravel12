@extends('layouts.dashboard')

@section('title', 'User Dashboard')

@section('sidebar')
    <a href="{{ route('user.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#"><i class="fas fa-user"></i> Profile</a>
    <a href="#"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="#"><i class="fas fa-cog"></i> Settings</a>
@endsection

@section('page_title', 'Welcome, ' . Auth::user()->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x text-primary"></i>
                <h5 class="mt-3">My Orders</h5>
                <p>Check your order history.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="fas fa-user fa-2x text-success"></i>
                <h5 class="mt-3">Profile</h5>
                <p>Manage your account info.</p>
            </div>
        </div>
    </div>
</div>
@endsection
