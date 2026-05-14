@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-2">Client Dashboard</h2>
    <p class="mb-4">Welcome, {{ Auth::user()->name }}</p>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center h-100">
                <i class="fas fa-pills fa-3x mb-3 text-primary"></i>
                <h5>Medicines</h5>
                <p>Browse available medicines.</p>
                <a href="{{ route('client.medicines') }}" class="btn btn-primary">View Medicines</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center h-100">
                <i class="fas fa-shopping-cart fa-3x mb-3 text-warning"></i>
                <h5>Cart</h5>
                <p>Check selected medicines.</p>
                <a href="{{ route('client.cart') }}" class="btn btn-warning">My Cart</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center h-100">
                <i class="fas fa-clipboard-list fa-3x mb-3 text-success"></i>
                <h5>Orders</h5>
                <p>View your previous orders.</p>
                <a href="{{ route('client.orders') }}" class="btn btn-success">My Orders</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center h-100">
                <i class="fas fa-user-edit fa-3x mb-3 text-info"></i>
                <h5>Profile</h5>
                <p>Update address and phone.</p>
                <a href="{{ route('client.profile') }}" class="btn btn-info">My Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection