@extends('layouts.app')

@section('title', 'Collect Student Fee')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Collect Student Fee</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fee-collection.index') }}">Fee Collections</a></li>
                <li class="breadcrumb-item active" aria-current="page">Collect Fee</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('fee-collection.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card-box">
            <h5 class="text-primary mb-4 border-bottom pb-2"><i class="fa-solid fa-circle-dollar-to-slot me-2"></i>Fee Receipt Generation</h5>
            
            <form action="{{ route('fee-collection.store') }}" method="POST">
                @csrf
                
                <!-- 1. Student Selection -->
                <div class="mb-4">
                    <label for="student_id" class="form-label">Select Student (Active Only)</label>
                    <select name="student_id" id="student_id" class="form-select" required>
                        <option value="">Search and select student...</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->name }} (S/O: {{ $st->father_name }}) - {{ $st->admission_number }} [{{ $st->class->name }}]</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Student Info Card (Initially Hidden) -->
                <div id="student-info-card" class="card-box bg-light border-0 shadow-none d-none mb-4">
                    <h6 class="text-secondary fw-semibold mb-3"><i class="fa-solid fa-address-card me-2"></i>Student Details</h6>
                    <div class="row g-3 small">
                        <div class="col-6 col-sm-3 text-muted">Student Name:</div>
                        <div class="col-6 col-sm-3 fw-bold text-dark" id="info-name">-</div>
                        
                        <div class="col-6 col-sm-3 text-muted">Father Name:</div>
                        <div class="col-6 col-sm-3 fw-semibold text-dark" id="info-father">-</div>
                        
                        <div class="col-6 col-sm-3 text-muted">Class:</div>
                        <div class="col-6 col-sm-3 fw-semibold text-dark" id="info-class">-</div>
                        
                        <div class="col-6 col-sm-3 text-muted">Current Arrears:</div>
                        <div class="col-6 col-sm-3 fw-bold text-warning" id="info-arrears">Rs. 0.00</div>
                    </div>
                </div>

                <!-- 3. Form Inputs -->
                <div class="row g-3 mb-4">
                    <!-- Date -->
                    <div class="col-12 col-md-6">
                        <label for="date" class="form-label">Collection Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Admission Fee -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="admission_fee" class="form-label">Admission Fee (Rs.)</label>
                        <input type="number" name="admission_fee" id="admission_fee" class="form-control fee-field" value="0" min="0" required>
                    </div>

                    <!-- Monthly Tuition Fee -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="monthly_fee" class="form-label">Monthly Fee (Rs.)</label>
                        <input type="number" name="monthly_fee" id="monthly_fee" class="form-control fee-field" value="0" min="0" required>
                    </div>

                    <!-- Exam Fee -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="exam_fee" class="form-label">Exam Fee (Rs.)</label>
                        <input type="number" name="exam_fee" id="exam_fee" class="form-control fee-field" value="0" min="0" required>
                    </div>

                    <!-- Arrears display (Readonly, loaded dynamically) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="arrears" class="form-label">Previous Arrears (Rs.)</label>
                        <input type="number" name="arrears" id="arrears" class="form-control bg-light" value="0" readonly>
                    </div>

                    <!-- Total Amount (Auto Calculated) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="total_amount" class="form-label">Total Amount Due (Rs.)</label>
                        <input type="number" name="total_amount" id="total_amount" class="form-control bg-light fw-bold text-secondary" value="0" readonly>
                    </div>

                    <!-- Paid Amount (User input) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="paid_amount" class="form-label text-success fw-bold">Amount Paid (Rs.)</label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control border-success fw-bold" value="0" min="0" required>
                    </div>

                    <!-- Remaining Arrears (Auto Calculated) -->
                    <div class="col-12 col-sm-6 col-md-6">
                        <label for="remaining_arrears" class="form-label text-warning">Remaining Outstanding Arrears (Rs.)</label>
                        <input type="number" name="remaining_arrears" id="remaining_arrears" class="form-control bg-light fw-semibold text-warning" value="0" readonly>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fa-solid fa-check me-2"></i>Collect & Generate Receipt</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side Information Card (Col 4) -->
    <div class="col-12 col-xl-4">
        <div class="card-box bg-light border-0 shadow-none">
            <h5 class="text-secondary mb-3"><i class="fa-solid fa-scale-unbalanced me-2"></i>Arrears Calculation</h5>
            <p class="small text-muted mb-3">The outstanding arrears balance is updated automatically upon collection submission.</p>
            <div class="bg-white p-3 border rounded-3 mb-3 small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Due</span>
                    <span class="fw-semibold" id="side-total">Rs. 0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Paid Cash</span>
                    <span class="fw-semibold text-success" id="side-paid">Rs. 0.00</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">New Arrears Balance</span>
                    <span class="fw-bold text-warning" id="side-remaining">Rs. 0.00</span>
                </div>
            </div>
            <div class="alert alert-info border-0 py-2 small mb-0">
                <i class="fa-solid fa-circle-exclamation me-1"></i> Fee totals entered here are verified and calculated on the backend. Frontend values are computed for immediate user visualization.
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const studentSelect = document.getElementById('student_id');
        const infoCard = document.getElementById('student-info-card');
        
        // Info fields
        const infoName = document.getElementById('info-name');
        const infoFather = document.getElementById('info-father');
        const infoClass = document.getElementById('info-class');
        const infoArrears = document.getElementById('info-arrears');

        // Form fields
        const admFeeInput = document.getElementById('admission_fee');
        const monthlyFeeInput = document.getElementById('monthly_fee');
        const examFeeInput = document.getElementById('exam_fee');
        const arrearsInput = document.getElementById('arrears');
        const totalInput = document.getElementById('total_amount');
        const paidInput = document.getElementById('paid_amount');
        const remainingInput = document.getElementById('remaining_arrears');

        // Side displays
        const sideTotal = document.getElementById('side-total');
        const sidePaid = document.getElementById('side-paid');
        const sideRemaining = document.getElementById('side-remaining');

        // 1. AJAX load student details
        studentSelect.addEventListener('change', function () {
            const studentId = studentSelect.value;
            if (!studentId) {
                infoCard.classList.add('d-none');
                resetForm();
                return;
            }

            fetch(`{{ url('/fee-collection/student') }}/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    // Update Details Card
                    infoName.textContent = data.name;
                    infoFather.textContent = data.father_name;
                    infoClass.textContent = data.class_name;
                    infoArrears.textContent = 'Rs. ' + data.arrears.toFixed(2);
                    infoCard.classList.remove('d-none');

                    // Pre-fill Form Fields
                    arrearsInput.value = data.arrears;
                    
                    // Monthly fee is loaded by default as standard. Admission and Exam are kept at 0 unless checked
                    monthlyFeeInput.value = data.default_fees.monthly_fee;
                    admFeeInput.value = 0; // Don't pre-fill admission fee by default on monthly collections
                    examFeeInput.value = 0; // Don't pre-fill exam fee by default on monthly collections
                    
                    paidInput.value = data.default_fees.monthly_fee; // Default paid amount to monthly tuition fee

                    calculateDues();
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                    alert('Error loading student records. Please try again.');
                });
        });

        // 2. Perform Javascript Dues calculations
        function calculateDues() {
            const adm = parseFloat(admFeeInput.value) || 0;
            const monthly = parseFloat(monthlyFeeInput.value) || 0;
            const exam = parseFloat(examFeeInput.value) || 0;
            const arrears = parseFloat(arrearsInput.value) || 0;

            const total = adm + monthly + exam + arrears;
            const paid = parseFloat(paidInput.value) || 0;
            const remaining = total - paid;

            totalInput.value = total.toFixed(2);
            remainingInput.value = remaining.toFixed(2);

            // Update Side Summary Box
            sideTotal.textContent = 'Rs. ' + total.toFixed(2);
            sidePaid.textContent = 'Rs. ' + paid.toFixed(2);
            sideRemaining.textContent = 'Rs. ' + remaining.toFixed(2);
        }

        // Event listeners on input adjustments
        document.querySelectorAll('.fee-field').forEach(input => {
            input.addEventListener('input', calculateDues);
        });
        paidInput.addEventListener('input', calculateDues);

        function resetForm() {
            admFeeInput.value = 0;
            monthlyFeeInput.value = 0;
            examFeeInput.value = 0;
            arrearsInput.value = 0;
            totalInput.value = 0;
            paidInput.value = 0;
            remainingInput.value = 0;
            sideTotal.textContent = 'Rs. 0.00';
            sidePaid.textContent = 'Rs. 0.00';
            sideRemaining.textContent = 'Rs. 0.00';
        }
    });
</script>
@endsection
