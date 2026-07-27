@extends('layouts.app')

@section('title', 'New Student Admission')

@section('content')
<div class="page-title-box">
    <div>
        <h3>New Student Admission</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Admission</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('admissions.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
    </div>
</div>

<div class="card-box">
    <h5 class="text-primary mb-4 border-bottom pb-2"><i class="fa-solid fa-user-plus me-2"></i>Admission Application Form</h5>
    
    <form action="{{ route('admissions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- SECTION 1: Personal & Demographic Information -->
        <h6 class="text-secondary fw-semibold mb-3">1. Student Information</h6>
        <div class="row g-3 mb-4">
            <!-- Full Name -->
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Student Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Ali Ahmed" required>
            </div>
            
            <!-- Father Name -->
            <div class="col-12 col-md-6">
                <label for="father_name" class="form-label">Father Name</label>
                <input type="text" name="father_name" id="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="e.g. Ahmed Khan" required>
            </div>

            <!-- Date of Birth -->
            <div class="col-12 col-sm-6 col-md-4">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
            </div>

            <!-- Gender -->
            <div class="col-12 col-sm-6 col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="">Select Gender...</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Contact Phone -->
            <div class="col-12 col-md-4">
                <label for="phone" class="form-label">Contact Phone No.</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. 0300-1234567">
            </div>

            <!-- Permanent Address -->
            <div class="col-12 col-md-8">
                <label for="address" class="form-label">Residential Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Street address, City, Sindh" required>
            </div>

            <!-- Student Photo -->
            <div class="col-12 col-md-4">
                <label for="photo" class="form-label">Passport Photo</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
        </div>

        <!-- SECTION 2: Academic Program Enrollment -->
        <h6 class="text-secondary fw-semibold mb-3">2. Enrollment Details</h6>
        <div class="row g-3 mb-4">
            <!-- Select Target Class -->
            <div class="col-12 col-md-4">
                <label for="class_id" class="form-label">Select Class</label>
                <select name="class_id" id="class_id" class="form-select" required>
                    <option value="" selected>Select target class...</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" 
                                data-admission-fee="{{ $cls->feeSetting->admission_fee ?? 3000.00 }}" 
                                data-monthly-fee="{{ $cls->feeSetting->monthly_fee ?? 2000.00 }}" 
                                data-exam-fee="{{ $cls->feeSetting->exam_fee ?? 500.00 }}">
                            {{ $cls->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Roll Number -->
            <div class="col-12 col-sm-6 col-md-4">
                <label for="roll_number" class="form-label">Roll Number</label>
                <input type="text" name="roll_number" id="roll_number" class="form-control" value="{{ old('roll_number') }}" placeholder="e.g. 501" required>
            </div>

            <!-- Admission Date -->
            <div class="col-12 col-sm-6 col-md-4">
                <label for="admission_date" class="form-label">Admission Date</label>
                <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ old('admission_date', date('Y-m-d')) }}" required>
            </div>
        </div>

        <!-- SECTION 3: Fee Configurations -->
        <h6 class="text-secondary fw-semibold mb-3">3. Applicable Fee Settings & Outstanding Balance</h6>
        <div class="row g-3 mb-4">
            <!-- Admission Fee -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="admission_fee" class="form-label">Admission Fee (Rs.)</label>
                <input type="number" name="admission_fee" id="admission_fee" class="form-control fee-calc" value="{{ old('admission_fee', 3000) }}" min="0" required>
            </div>

            <!-- Monthly Tuition Fee -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="monthly_fee" class="form-label">Monthly Tuition Fee (Rs.)</label>
                <input type="number" name="monthly_fee" id="monthly_fee" class="form-control fee-calc" value="{{ old('monthly_fee', 2000) }}" min="0" required>
            </div>

            <!-- Exam Fee -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="exam_fee" class="form-label">Exam Fee (Rs.)</label>
                <input type="number" name="exam_fee" id="exam_fee" class="form-control fee-calc" value="{{ old('exam_fee', 500) }}" min="0" required>
            </div>

            <!-- Previous Arrears -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="arrears" class="form-label">Initial Arrears (Rs.)</label>
                <input type="number" name="arrears" id="arrears" class="form-control fee-calc" value="{{ old('arrears', 0) }}" min="0">
            </div>
        </div>

        <!-- SECTION 4: Payment Transaction -->
        <h6 class="text-secondary fw-semibold mb-3">4. Immediate Fee Collection (Optional)</h6>
        <div class="card-box bg-light border-0 shadow-none mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="collect_admission_fee" id="collect_admission_fee" value="1" {{ old('collect_admission_fee') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="collect_admission_fee">Collect Payment Immediately</label>
                    </div>
                </div>
                
                <div class="col-12 col-sm-4 d-none" id="payment-input-group">
                    <label for="paid_amount" class="form-label">Amount Paid Now (Rs.)</label>
                    <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount', 0) }}" min="0">
                </div>

                <div class="col-12 col-sm-4 text-end">
                    <div class="fw-bold fs-5 text-secondary">
                        Grand Total: <span class="text-primary" id="grand-total">Rs. 0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fa-solid fa-check me-2"></i>Save Admission</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classSelect = document.getElementById('class_id');
        const admFeeInput = document.getElementById('admission_fee');
        const monthlyFeeInput = document.getElementById('monthly_fee');
        const examFeeInput = document.getElementById('exam_fee');
        const arrearsInput = document.getElementById('arrears');
        const grandTotalText = document.getElementById('grand-total');

        const collectCheck = document.getElementById('collect_admission_fee');
        const paymentGroup = document.getElementById('payment-input-group');
        const paidAmountInput = document.getElementById('paid_amount');

        // 1. Update Fees based on Selected Class Data Attributes
        classSelect.addEventListener('change', function () {
            const selectedOption = classSelect.options[classSelect.selectedIndex];
            if (selectedOption && selectedOption.value !== '') {
                admFeeInput.value = selectedOption.getAttribute('data-admission-fee');
                monthlyFeeInput.value = selectedOption.getAttribute('data-monthly-fee');
                examFeeInput.value = selectedOption.getAttribute('data-exam-fee');
            } else {
                admFeeInput.value = 3000;
                monthlyFeeInput.value = 2000;
                examFeeInput.value = 500;
            }
            calculateTotal();
        });

        // 2. Calculate Total dynamically
        function calculateTotal() {
            const adm = parseFloat(admFeeInput.value) || 0;
            const monthly = parseFloat(monthlyFeeInput.value) || 0;
            const exam = parseFloat(examFeeInput.value) || 0;
            const arr = parseFloat(arrearsInput.value) || 0;

            const total = adm + monthly + exam + arr;
            grandTotalText.textContent = 'Rs. ' + total.toFixed(2);
            
            // Set default paid amount if checked
            if (collectCheck.checked && paidAmountInput.value == 0) {
                // Default to paying the admission fee immediately
                paidAmountInput.value = adm;
            }
        }

        // Add event listeners on input fields
        document.querySelectorAll('.fee-calc').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        // 3. Toggle Immediate Payment inputs
        collectCheck.addEventListener('change', function () {
            if (collectCheck.checked) {
                paymentGroup.classList.remove('d-none');
                paidAmountInput.setAttribute('required', 'required');
                calculateTotal();
            } else {
                paymentGroup.classList.add('d-none');
                paidAmountInput.removeAttribute('required');
                paidAmountInput.value = 0;
            }
        });

        // Initial trigger
        calculateTotal();
        if (collectCheck.checked) {
            paymentGroup.classList.remove('d-none');
        }
    });
</script>
@endsection
