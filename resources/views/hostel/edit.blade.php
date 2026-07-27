@extends('layouts.app')

@section('title', 'Edit ' . $config['name'])

@section('content')
<div class="page-title-box">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit {{ $config['name'] }} Entry</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.index', $category) }}">{{ $config['name'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Record</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card-box" style="max-width: 800px;">
    <h5 class="text-primary mb-4"><i class="{{ $config['icon'] }} me-2"></i>Update Transaction Details</h5>

    <form method="POST" action="{{ route('hostel.update', [$category, $expenditure->id]) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Transaction Date -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment / Transaction Date <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $expenditure->date->toDateString()) }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Amount -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Amount (PKR) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">Rs.</span>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" value="{{ old('amount', $expenditure->amount) }}" required>
                </div>
                @error('amount')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Title (Required if in config) -->
            @if(in_array('title', $config['fields']))
            <div class="col-12">
                <label class="form-label fw-semibold">
                    @if($category === 'rent')
                        Building / Room / Facility Name <span class="text-danger">*</span>
                    @else
                        Title / Description <span class="text-danger">*</span>
                    @endif
                </label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       placeholder="{{ $category === 'rent' ? 'e.g. Hostel Main Building, Room 14' : 'e.g. Grocery and Food Supplies, Plumbing Repair' }}" 
                       value="{{ old('title', $expenditure->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <!-- Payee Name (Required if in config) -->
            @if(in_array('payee_name', $config['fields']))
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    @if($category === 'salaries')
                        Staff Member Name <span class="text-danger">*</span>
                    @elseif($category === 'rent')
                        Landlord / Payee Name <span class="text-danger">*</span>
                    @else
                        Vendor / Payee Name
                    @endif
                </label>
                <input type="text" name="payee_name" class="form-control @error('payee_name') is-invalid @enderror" 
                       placeholder="{{ $category === 'salaries' ? 'e.g. Hammad Khan, Ahmed Shah' : 'e.g. Landlord/Vendor Name' }}" 
                       value="{{ old('payee_name', $expenditure->payee_name) }}" required>
                @error('payee_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <!-- Billing Month (Required if in config) -->
            @if(in_array('billing_month', $config['fields']))
            <div class="col-md-6">
                <label class="form-label fw-semibold">Billing Month / Period <span class="text-danger">*</span></label>
                <input type="month" name="billing_month" class="form-control @error('billing_month') is-invalid @enderror" 
                       value="{{ old('billing_month', $expenditure->billing_month) }}" required>
                @error('billing_month')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <!-- Reference Number (Required or optional depending on config) -->
            @if(in_array('reference_no', $config['fields']))
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    @if($category === 'electric-bill')
                        Consumer / Reference Number <span class="text-danger">*</span>
                    @else
                        Bill / Voucher / Reference No.
                    @endif
                </label>
                <input type="text" name="reference_no" class="form-control @error('reference_no') is-invalid @enderror" 
                       placeholder="{{ $category === 'electric-bill' ? 'e.g. 14-55895-1234567 U' : 'e.g. Receipt No. or transaction ID' }}" 
                       value="{{ old('reference_no', $expenditure->reference_no) }}" {{ $category === 'electric-bill' ? 'required' : '' }}>
                @error('reference_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <!-- Units Consumed (Electric Bill Only) -->
            @if(in_array('units_consumed', $config['fields']))
            <div class="col-md-6">
                <label class="form-label fw-semibold">Units Consumed</label>
                <input type="number" name="units_consumed" class="form-control @error('units_consumed') is-invalid @enderror" placeholder="e.g. 450" value="{{ old('units_consumed', $expenditure->units_consumed) }}">
                @error('units_consumed')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <!-- Payment Method -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    <option value="Cash" {{ old('payment_method', $expenditure->payment_method) === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_method', $expenditure->payment_method) === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ old('payment_method', $expenditure->payment_method) === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="EasyPaisa" {{ old('payment_method', $expenditure->payment_method) === 'EasyPaisa' ? 'selected' : '' }}>EasyPaisa</option>
                    <option value="JazzCash" {{ old('payment_method', $expenditure->payment_method) === 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                    <option value="Card" {{ old('payment_method', $expenditure->payment_method) === 'Card' ? 'selected' : '' }}>Debit/Credit Card</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-semibold">Additional Notes / Remarks</label>
                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Provide any details about the payment, vendor, or reasons...">{{ old('notes', $expenditure->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="col-12 mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    @if($category === 'salaries')
                        Update Salary Payment
                    @elseif($category === 'rent')
                        Update Rent Payment
                    @elseif($category === 'electric-bill')
                        Update Electric Bill
                    @elseif($category === 'expenditures')
                        Update Expense Entry
                    @else
                        Update {{ $config['name'] }}
                    @endif
                </button>
                <a href="{{ route('hostel.index', $category) }}" class="btn btn-light border">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
