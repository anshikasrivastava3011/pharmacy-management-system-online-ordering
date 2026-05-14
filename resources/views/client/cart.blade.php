@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <form method="POST" action="{{ route('client.cart.update') }}">
            @csrf

            <div class="card p-4">
                @php $total = 0; @endphp

                @foreach($cart as $medicineId => $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <strong>{{ $item['name'] }}</strong>
                        </div>

                        <div class="col-md-2">
                            ₹{{ $item['price'] }}
                        </div>

                        <div class="col-md-2">
                            <input type="number"
                                   name="quantities[{{ $medicineId }}]"
                                   value="{{ $item['quantity'] }}"
                                   min="1"
                                   class="form-control">
                        </div>

                        <div class="col-md-2">
                            ₹{{ $subtotal }}
                        </div>

                        <div class="col-md-2">
                            <a href="{{ route('client.cart.remove', $medicineId) }}"
                               class="btn btn-danger btn-sm">
                                Remove
                            </a>
                        </div>
                    </div>
                @endforeach

                <hr>

                <h4>Total: ₹{{ $total }}</h4>

                <button type="submit" class="btn btn-primary">
                    Update Cart
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('client.cart.placeOrder') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-success">
                Place Order
            </button>
        </form>
    @else
        <div class="alert alert-info">
            Your cart is empty.
        </div>
    @endif
</div>
@endsection