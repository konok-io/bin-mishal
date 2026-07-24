@extends('layouts.admin')
@section('title', 'New Invoice')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> New Invoice</h1>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.invoices.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->user->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Invoice title">
                </div>
            </div>
            <hr>
            <h5>Items</h5>
            <div id="items-container">
                <div class="row item-row mb-2">
                    <div class="col-md-8">
                        <input type="text" name="items[0][description]" class="form-control" placeholder="Description" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[0][unit_price]" class="form-control" placeholder="Price" step="0.01" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-item"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" id="add-item"><i class="bi bi-plus"></i> Add Item</button>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Create Invoice
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('add-item').onclick = function() {
    const container = document.getElementById('items-container');
    const index = container.children.length;
    const html = `<div class="row item-row mb-2">
        <div class="col-md-8">
            <input type="text" name="items[${index}][description]" class="form-control" placeholder="Description" required>
        </div>
        <div class="col-md-3">
            <input type="number" name="items[${index}][unit_price]" class="form-control" placeholder="Price" step="0.01" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-item"><i class="bi bi-trash"></i></button>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
};
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        e.target.closest('.item-row').remove();
    }
});
</script>
@endpush
@endsection
