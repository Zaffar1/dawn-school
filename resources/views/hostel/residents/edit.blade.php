@extends('layouts.app')

@section('title', 'Edit Hostel Student/Staff Details')

@section('content')
<div class="page-title-box">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-user-pen me-2 text-primary"></i>Edit Student/Staff Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.residents.index') }}">Students Directory</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card-box" style="max-width: 800px;">
    <h5 class="text-primary mb-4"><i class="fa-solid fa-id-card me-2"></i>Person Profile Details</h5>

    <form method="POST" action="{{ route('hostel.residents.update', $resident->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Resident Type -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Person Type <span class="text-danger">*</span></label>
                <select name="resident_type" id="resident_type" class="form-select @error('resident_type') is-invalid @enderror" required>
                    <option value="student" {{ (old('resident_type', $resident->resident_type) === 'student' || old('resident_type', $resident->resident_type) === 'resident') ? 'selected' : '' }}>Hostel Student</option>
                    <option value="staff" {{ old('resident_type', $resident->resident_type) === 'staff' ? 'selected' : '' }}>Hostel Staff</option>
                </select>
                @error('resident_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Resident Name -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name of person" value="{{ old('name', $resident->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Resident Phone -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Contact Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. +923001234567" value="{{ old('phone', $resident->phone ?? '+92') }}">
                <div class="invalid-feedback" id="phone_error_feedback"></div>
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Room Number -->
            <div class="col-md-6" id="room_number_container">
                <label class="form-label fw-semibold" id="room_number_label">Room Assigned <span class="text-danger">*</span></label>
                <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" placeholder="e.g. Room-101, Room-203" value="{{ old('room_number', $resident->room_number) }}" required>
                @error('room_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Monthly Fee -->
            <div class="col-md-6">
                <label class="form-label fw-semibold" id="monthly_fee_label">Monthly Hostel Charge (PKR) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">Rs.</span>
                    <input type="number" step="0.01" name="monthly_fee" id="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror" placeholder="0.00" value="{{ old('monthly_fee', $resident->monthly_fee) }}" required>
                </div>
                @error('monthly_fee')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deposit [Advance] -->
            <div class="col-md-6" id="deposit_container">
                <label class="form-label fw-semibold" id="deposit_label">Deposit [Advance] (PKR)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">Rs.</span>
                    <input type="number" step="0.01" name="deposit" id="deposit" class="form-control @error('deposit') is-invalid @enderror" placeholder="0.00" value="{{ old('deposit', $resident->deposit) }}">
                </div>
                @error('deposit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Joining Date -->
            <div class="col-md-6">
                <label class="form-label fw-semibold" id="joining_date_label">Hostel Joining Date <span class="text-danger">*</span></label>
                <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', $resident->joining_date->toDateString()) }}" required>
                @error('joining_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Leaving Date -->
            <div class="col-md-6" id="leaving_date_container">
                <label class="form-label fw-semibold">Leaving Date</label>
                <input type="date" name="leaving_date" class="form-control @error('leaving_date') is-invalid @enderror" value="{{ old('leaving_date', $resident->leaving_date ? $resident->leaving_date->toDateString() : '') }}">
                @error('leaving_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Resident Status -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status', $resident->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $resident->status) === 'inactive' ? 'selected' : '' }}>Inactive/Left</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-semibold" id="notes_label">Hostel / Student Notes</label>
                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Enter emergency contacts, instructions, or remarks...">{{ old('notes', $resident->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="col-12 mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submit_btn"><i class="fa-solid fa-floppy-disk me-1"></i> Update Student / Staff Details</button>
                <a href="{{ route('hostel.residents.index') }}" class="btn btn-light border">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone_error_feedback');
        const prefix = '+92';

        // Set initial prefix if field is empty
        if (phoneInput.value.trim() === '') {
            phoneInput.value = prefix;
        }

        phoneInput.addEventListener('input', function() {
            let val = phoneInput.value;

            // Ensure value always starts with +92
            if (!val.startsWith(prefix)) {
                phoneInput.value = prefix;
                return;
            }

            // Get digits typed after +92
            let typed = val.substring(prefix.length);
            
            // Clean non-numeric characters
            let digits = typed.replace(/[^0-9]/g, '');

            // Auto-strip leading '0' if they type '03...' instead of '3...'
            if (digits.startsWith('0')) {
                digits = digits.substring(1);
            }

            // Restrict to exactly 10 digits after +92 (total length 13)
            if (digits.length > 10) {
                digits = digits.substring(0, 10);
            }

            // Update input value
            phoneInput.value = prefix + digits;

            validatePhone();
        });

        phoneInput.addEventListener('keydown', function(e) {
            // Prevent deleting the +92 prefix with backspace or delete key
            if (phoneInput.selectionStart < prefix.length && 
                (e.key === 'Backspace' || e.key === 'Delete')) {
                e.preventDefault();
            }
        });

        function validatePhone() {
            const val = phoneInput.value;
            // Empty (just +92) is allowed since phone is optional
            if (val === prefix) {
                phoneInput.classList.remove('is-invalid', 'is-valid');
                return true;
            }

            if (val.length === 13) {
                phoneInput.classList.remove('is-invalid');
                phoneInput.classList.add('is-valid');
                return true;
            } else {
                phoneInput.classList.remove('is-valid');
                phoneInput.classList.add('is-invalid');
                phoneError.innerText = "Please enter exactly 10 digits after +92 (e.g. +923001234567).";
                return false;
            }
        }

        // Dynamic fields updater for Staff vs Student
        const typeSelect = document.getElementById('resident_type');
        const feeLabel = document.getElementById('monthly_fee_label');
        const feeInput = document.getElementById('monthly_fee');
        const roomInput = document.getElementById('room_number');
        const joinLabel = document.getElementById('joining_date_label');
        const notesLabel = document.getElementById('notes_label');
        const notesInput = document.getElementById('notes');
        const depositContainer = document.getElementById('deposit_container');
        const roomContainer = document.getElementById('room_number_container');

        function updateLabels() {
            const isStaff = typeSelect.value === 'staff';
            if (isStaff) {
                feeLabel.innerHTML = 'Monthly Salary (PKR) <span class="text-danger">*</span>';
                feeInput.placeholder = 'e.g. 25000';
                joinLabel.innerHTML = 'Employment / Joining Date <span class="text-danger">*</span>';
                notesLabel.innerText = 'Staff Notes / Remarks';
                notesInput.placeholder = 'Enter designation, shift timing, or other notes...';
                depositContainer.style.display = 'none';
                roomContainer.style.display = 'none';
                roomInput.required = false;
                if (roomInput.value === '') {
                    roomInput.value = 'N/A';
                }
            } else {
                feeLabel.innerHTML = 'Monthly Hostel Charge (PKR) <span class="text-danger">*</span>';
                feeInput.placeholder = '0.00';
                joinLabel.innerHTML = 'Hostel Joining Date <span class="text-danger">*</span>';
                notesLabel.innerText = 'Hostel / Student Notes';
                notesInput.placeholder = 'Enter emergency contacts, instructions, or remarks...';
                depositContainer.style.display = 'block';
                roomContainer.style.display = 'block';
                roomInput.required = true;
                if (roomInput.value === 'N/A') {
                    roomInput.value = '';
                }
            }
        }

        typeSelect.addEventListener('change', updateLabels);
        updateLabels();

        // Run initially
        validatePhone();
    });
</script>
@endsection
