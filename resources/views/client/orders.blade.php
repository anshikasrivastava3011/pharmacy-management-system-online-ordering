@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
        <div class="card p-3 mb-3">
            <h5>Order #{{ $order->id }}</h5>

            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Total Price:</strong> ₹{{ $order->price }}</p>

            <h6>Medicines Ordered:</h6>
            @foreach($order->medicines as $medicine)
                <p>
                    {{ $medicine->commercial_name ?? $medicine->scientific_name ?? $medicine->name ?? 'Medicine' }}
                    - Quantity: {{ $medicine->pivot->quantity }}
                </p>
            @endforeach

            @if($order->address)
                <p>
                    <strong>Delivery Address:</strong>
                    {{ $order->address->street_name }},
                    Building {{ $order->address->building_number }},
                    Floor {{ $order->address->floor_number }},
                    Flat {{ $order->address->flat_number }}
                </p>
            @endif
        </div>
    @empty
        <div class="alert alert-info">
            You have not placed any orders yet.
        </div>
    @endforelse
</div>
@endsection