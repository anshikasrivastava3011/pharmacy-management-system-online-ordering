@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Place Order</h2>

    <div class="card p-4">
        <h4>
            {{ $medicine->commercial_name ?? $medicine->scientific_name ?? $medicine->name }}
        </h4>

        <p><strong>Price:</strong> ₹{{ $medicine->price }}</p>

        <form method="POST" action="{{ route('client.order.store') }}">
            @csrf

            <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">

            <div class="form-group">
                <label>Quantity</label>
                <input type="number"
                       name="quantity"
                       class="form-control"
                       min="1"
                       required>
            </div>

            <button type="submit" class="btn btn-success mt-3">
                Confirm Order
            </button>
        </form>
    </div>
</div>
@endsection