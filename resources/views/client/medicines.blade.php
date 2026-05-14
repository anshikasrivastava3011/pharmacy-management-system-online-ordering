@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Available Medicines</h2>
            <p class="text-muted mb-0">Search medicines and add them to your cart.</p>
        </div>

        <a href="{{ route('client.cart') }}" class="btn btn-outline-warning px-4 py-2">
            <i class="fas fa-shopping-cart"></i> View Cart
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-3 mb-4 search-card">
        <div class="row">
            <div class="col-md-8 mb-2">
                <input type="text" id="medicineSearch" class="form-control"
                       placeholder="Search medicine by name...">
            </div>

            <div class="col-md-4 mb-2">
                <select id="stockFilter" class="form-control">
                    <option value="all">All Medicines</option>
                    <option value="available">Available Only</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row" id="medicineList">
        @forelse($medicines as $medicine)
            @php
                $medicineName = $medicine->commercial_name 
                    ?? $medicine->scientific_name 
                    ?? $medicine->name 
                    ?? $medicine->trade_name 
                    ?? 'Medicine Name';

                $quantity = $medicine->quantity ?? $medicine->stock ?? 0;
            @endphp

            <div class="col-md-4 mb-4 medicine-card"
                 data-name="{{ strtolower($medicineName) }}"
                 data-stock="{{ $quantity > 0 ? 'available' : 'out' }}">

                <div class="card medicine-modern-card">
                    <div class="card-body d-flex flex-column text-center">

                        <h5 class="mb-2 font-weight-bold">{{ $medicineName }}</h5>

                        <p class="mb-2">
                            <strong>₹{{ $medicine->price ?? 'N/A' }}</strong>
                        </p>

                        <p class="mb-3">
                            @if($quantity > 0)
                                <span class="badge badge-success px-3 py-2">
                                    {{ $quantity }} in stock
                                </span>
                            @else
                                <span class="badge badge-danger px-3 py-2">
                                    Out of Stock
                                </span>
                            @endif
                        </p>

                        @if($quantity > 0)
                            <a href="{{ route('client.cart.add', $medicine->id) }}"
                               class="btn btn-success btn-sm mt-auto">
                                Add to Cart
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm mt-auto" disabled>
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    No medicines are available right now.
                </div>
            </div>
        @endforelse
    </div>

    <div id="noMedicineFound" class="alert alert-info d-none">
        No medicine found matching your search.
    </div>
</div>

<style>
    .search-card {
        border-radius: 14px;
        border: none;
    }

    .medicine-modern-card {
        border-radius: 16px;
        min-height: 205px;
        border: none;
        box-shadow: 0 5px 16px rgba(0, 0, 0, 0.15);
        transition: 0.3s ease;
    }

    .medicine-modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.22);
    }

    #medicineSearch,
    #stockFilter {
        border-radius: 10px;
        height: 46px;
    }

    .btn-outline-warning {
        border-width: 2px;
        color: #d39e00;
        border-color: #d6b45c;
    }

    .btn-outline-warning:hover {
        background-color: #d6b45c;
        border-color: #d6b45c;
        color: #1f252b;
    }
</style>
@endsection

@section('scripts')
<script>
    const searchInput = document.getElementById('medicineSearch');
    const stockFilter = document.getElementById('stockFilter');
    const medicineCards = document.querySelectorAll('.medicine-card');
    const noMedicineFound = document.getElementById('noMedicineFound');

    function filterMedicines() {
        const searchText = searchInput.value.toLowerCase();
        const stockValue = stockFilter.value;
        let visibleCount = 0;

        medicineCards.forEach(card => {
            const medicineName = card.getAttribute('data-name');
            const stockStatus = card.getAttribute('data-stock');

            const matchesSearch = medicineName.includes(searchText);
            const matchesStock = stockValue === 'all' || stockStatus === stockValue;

            if (matchesSearch && matchesStock) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            noMedicineFound.classList.remove('d-none');
        } else {
            noMedicineFound.classList.add('d-none');
        }
    }

    searchInput.addEventListener('keyup', filterMedicines);
    stockFilter.addEventListener('change', filterMedicines);
</script>
@endsection