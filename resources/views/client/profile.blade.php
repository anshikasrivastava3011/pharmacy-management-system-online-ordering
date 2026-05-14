@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Profile</h2>
    <p>Update your contact and delivery address details.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4">
        <form method="POST" action="{{ route('client.profile.update') }}">
            @csrf

            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone', $client->phone) }}" required>
            </div>

            <h5 class="mt-4">Delivery Address</h5>

            <div class="form-group">
                <label>Area</label>
                <select name="area_id" class="form-control" required>
                    <option value="">Select Area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}"
                            {{ old('area_id', $address->area_id ?? $client->area_id) == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Street Name</label>
                <input type="text" name="street_name" class="form-control"
                       value="{{ old('street_name', $address->street_name ?? $client->street_name) }}" required>
            </div>

            <div class="form-group">
                <label>Building Number</label>
                <input type="number" name="building_number" class="form-control"
                       value="{{ old('building_number', $address->building_number ?? $client->building_no) }}" required>
            </div>

            <div class="form-group">
                <label>Floor Number</label>
                <input type="number" name="floor_number" class="form-control"
                       value="{{ old('floor_number', $address->floor_number ?? $client->floor_number) }}" required>
            </div>

            <div class="form-group">
                <label>Flat Number</label>
                <input type="number" name="flat_number" class="form-control"
                       value="{{ old('flat_number', $address->flat_number ?? $client->flat_number) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Profile
            </button>
        </form>
    </div>
</div>
@endsection