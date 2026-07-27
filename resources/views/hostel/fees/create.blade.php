@extends('layouts.app')

@section('title', 'Collect Hostel Fee')

@section('content')
<div class="page-title-box">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-hand-holding-dollar me-2 text-primary"></i>Collect Hostel Fee</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.resident-fees.index') }}">Fee Ledger</a></li>
                <li class="breadcrumb-item active" aria-current="page">Collect Payment</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card-box" style="max-width: 800px;">
    <h5 class="text-primary mb-4"><i class="fa-solid fa-file-invoice me-2"></i>Payment Collection Form</h5>

    <form method="POST" action="{{ route('hostel.resident-fees.store') }}">
        @csrf

        <div class="row g-3">
            <!-- Resident Selection -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Select Resident <span class="text-danger">*</span></label>
                <select name="hostel_resident_id" id="hostel_resident_id" class="form-select @error('hostel_resident_id') is-invalid @enderror" required>
                    <option value="">-- Select Resident --</option>
                    @foreach($residents as $res)
                        <option value="{{ $res->id }}" 
                            {{ (old('hostel_resident_id') == $res->id || (isset($selectedResident) && $selectedResident->id == $res->id)) ? 'selected' : '' }}
                            data-fee="{{ $res->monthly_fee }}" 
                            data-room="{{ $res->room_number }}"
                            data-type="{{ $res->resident_type }}">
                            {{ $res->name }} (Room: {{ $res->room_number }}, Type: {{ ucfirst($res->resident_type) }})
                        </option>
                    @endforeach
                </select>
                @error('hostel_resident_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Display Area for Selected Resident Details -->
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">Resident Details Summary</label>
                <div class="p-2 border rounded bg-light" style="min-height:38px;">
                    <span id="resident_summary_info" class="text-muted small">No resident selected.</span>
                </div>
            </div>

            <!-- Amount to Collect -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Amount Collected (PKR) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">Rs.</span>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" value="{{ old('amount') }}" required>
                </div>
                @error('amount')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Payment Date -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->toDateString()) }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Billing Month -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Billing Month <span class="text-danger">*</span></label>
                <input type="month" name="billing_month" class="form-control @error('billing_month') is-invalid @enderror" value="{{ old('billing_month', now()->format('Y-m')) }}" required>
                @error('billing_month')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Payment Method -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ old('payment_method') === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="EasyPaisa" {{ old('payment_method') === 'EasyPaisa' ? 'selected' : '' }}>EasyPaisa</option>
                    <option value="JazzCash" {{ old('payment_method') === 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                    <option value="Card" {{ old('payment_method') === 'Card' ? 'selected' : '' }}>Debit/Credit Card</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reference Number -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Reference Number / Transaction ID</label>
                <input type="text" name="reference_no" class="form-control @error('reference_no') is-invalid @enderror" placeholder="e.g. Bank slip, EasyPaisa transaction ID" value="{{ old('reference_no') }}">
                @error('reference_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-semibold">Remarks / Payment Notes</label>
                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Provide any extra details about the payment...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="col-12 mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Record Payment</button>
                <a href="{{ route('hostel.resident-fees.index') }}" class="btn btn-light border">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const residentSelect = document.getElementById('hostel_resident_id');
        const amountInput = document.getElementById('amount');
        const summaryInfo = document.getElementById('resident_summary_info');

        function updateResidentDetails() {
            const selectedOption = residentSelect.options[residentSelect.selectedIndex];
            if (selectedOption && selectedOption.value !== '') {
                const fee = selectedOption.getAttribute('data-fee');
                const room = selectedOption.getAttribute('data-room');
                const type = selectedOption.getAttribute('data-type');
                
                // Auto-fill amount collected
                if (amountInput.value === '') {
                    amountInput.value = fee;
                }
                
                // Display summary
                summaryInfo.innerHTML = `<strong>Room:</strong> ${room} | <strong>Monthly Fee rate:</strong> Rs. ${parseFloat(fee).toFixed(2)} | <strong>Type:</strong> ${type.toUpperCase()}`;
                summaryInfo.className = "text-dark small";
            } else {
                summaryInfo.innerText = "No resident selected.";
                summaryInfo.className = "text-muted small";
                amountInput.value = '';
            }
        }

        residentSelect.addEventListener('change', updateResidentDetails);

        // Run on load to pre-fill if parameter was sent
        updateResidentDetails();
    });
</script>
@endsection
