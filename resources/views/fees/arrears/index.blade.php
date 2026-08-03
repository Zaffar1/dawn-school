@extends('layouts.app')

@section('title', 'Student Arrears Management')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Student Arrears Management</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fee-collection.index') }}">Fee Collection</a></li>
                <li class="breadcrumb-item active" aria-current="page">Arrears</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Students with Arrears -->
    <div class="col-12 col-md-4">
        <div class="card-box h-100 border-start border-4 border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small">Students with Arrears</span>
                    <h3 class="mb-0 fw-bold mt-1 text-danger">{{ $totalStudents }}</h3>
                </div>
                <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-user-slash fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Outstanding Amount -->
    <div class="col-12 col-md-4">
        <div class="card-box h-100 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-semibold small">Total Outstanding Balance</span>
                    <h3 class="mb-0 fw-bold mt-1 text-warning">Rs. {{ number_format($totalOutstanding, 2) }}</h3>
                </div>
                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-wallet fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Month-wise Breakdown -->
    <div class="col-12 col-md-4">
        <div class="card-box h-100 border-start border-4 border-primary">
            <span class="text-muted text-uppercase fw-semibold small d-block mb-2">Month-wise Breakdown</span>
            <div class="d-flex flex-wrap gap-2 align-items-center" style="max-height: 55px; overflow-y: auto;">
                @forelse($monthWiseBreakdown as $breakdown)
                    @php
                        $formattedMonth = date('F Y', strtotime($breakdown->month . '-01'));
                    @endphp
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-2.5">
                        <strong>{{ $formattedMonth }}:</strong> Rs. {{ number_format($breakdown->total_amount, 0) }}
                    </span>
                @empty
                    <span class="text-muted fs-7">No active outstanding arrears.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card-box mb-4">
    <form action="{{ route('arrears.index') }}" method="GET" class="row g-3 align-items-end">
        <!-- Search Query -->
        <div class="col-12 col-md-3">
            <label for="search" class="form-label fw-medium small">Search Students</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Name, Roll No, Admission No..." value="{{ $search }}">
            </div>
        </div>

        <!-- Class Filter -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="class_id" class="form-label fw-medium small">Class</label>
            <select name="class_id" id="class_id" class="form-select">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Section Filter -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="section" class="form-label fw-medium small">Section</label>
            <select name="section" id="section" class="form-select">
                <option value="">All Sections</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec }}" {{ $sectionFilter === $sec ? 'selected' : '' }}>Section {{ $sec }}</option>
                @endforeach
            </select>
        </div>

        <!-- Month Filter -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="month" class="form-label fw-medium small">Month</label>
            <select name="month" id="month" class="form-select">
                <option value="">All Months</option>
                @foreach($distinctMonths as $m)
                    @php
                        $monthText = date('F Y', strtotime($m . '-01'));
                    @endphp
                    <option value="{{ $m }}" {{ $monthFilter === $m ? 'selected' : '' }}>{{ $monthText }}</option>
                @endforeach
            </select>
        </div>

        <!-- Payment Status Filter -->
        <div class="col-12 col-sm-6 col-md-2">
            <label for="payment_status" class="form-label fw-medium small">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-select">
                <option value="">All Statuses</option>
                <option value="unpaid" {{ $statusFilter === 'unpaid' ? 'selected' : '' }}>Unpaid Months</option>
                <option value="partially_paid" {{ $statusFilter === 'partially_paid' ? 'selected' : '' }}>Partially Paid Months</option>
                <option value="paid" {{ $statusFilter === 'paid' ? 'selected' : '' }}>Paid Months</option>
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i></button>
            <a href="{{ route('arrears.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="fa-solid fa-arrow-rotate-left"></i></a>
        </div>
    </form>
</div>

<!-- Arrears Table Card -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Student Details</th>
                    <th>Roll & ID</th>
                    <th>Class & Sec</th>
                    <th>Guardian Details</th>
                    <th>Outstanding Month(s)</th>
                    <th class="text-end">Total Arrears</th>
                    <th class="text-center" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div class="rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.9rem; font-weight: 600;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('students.show', $student->id) }}" class="fw-bold text-decoration-none text-dark">{{ $student->name }}</a>
                                    <div class="text-muted small">Status: 
                                        <span class="badge bg-success-subtle text-success py-0.5 px-1.5 border border-success-subtle" style="font-size: 0.65rem;">Active</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $student->roll_number }}</div>
                            <div class="text-muted small fs-7">{{ $student->admission_number }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-primary">{{ $student->class->name }}</div>
                            <div class="text-muted small">Section {{ $student->section }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $student->father_name }}</div>
                            <div class="text-muted small"><i class="fa-solid fa-phone me-1 text-muted" style="font-size: 0.75rem;"></i>{{ $student->phone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @forelse($student->studentArrears as $arr)
                                    @php
                                        $arrMonthText = date('M Y', strtotime($arr->month . '-01'));
                                        if ($arr->payment_status === 'paid') {
                                            $statusClass = 'bg-success-subtle text-success border-success-subtle';
                                            $statusLabel = 'Paid';
                                            $amtClass = 'text-muted';
                                        } elseif ($arr->payment_status === 'partially_paid') {
                                            $statusClass = 'bg-warning-subtle text-warning border-warning-subtle';
                                            $statusLabel = 'Partial';
                                            $amtClass = 'text-warning fw-bold';
                                        } else {
                                            $statusClass = 'bg-danger-subtle text-danger border-danger-subtle';
                                            $statusLabel = 'Unpaid';
                                            $amtClass = 'text-danger fw-bold';
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between gap-3 border-bottom pb-1" style="font-size: 0.8rem;">
                                        <span class="fw-semibold text-dark">{{ $arrMonthText }}</span>
                                        <span class="{{ $amtClass }}">Rs. {{ number_format($arr->amount, 2) }}</span>
                                        <span class="badge {{ $statusClass }} border py-0.5 px-1.5" style="font-size: 0.65rem;">{{ $statusLabel }}</span>
                                    </div>
                                @empty
                                    <span class="text-muted small">No detailed months.</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-dark fs-5">Rs. {{ number_format($student->arrears, 2) }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm px-3 py-1.5 collect-payment-btn" 
                                    data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->name }}"
                                    data-student-roll="{{ $student->roll_number }}"
                                    data-student-class="{{ $student->class->name }}"
                                    data-student-section="{{ $student->section }}"
                                    data-student-arrears="{{ $student->arrears }}"
                                    data-student-months='@json($student->studentArrears)'>
                                <i class="fa-solid fa-cash-register me-1.5"></i>Collect Payment
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-circle-info fs-3 mb-2 text-secondary"></i>
                            <div>No students found with outstanding arrears.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $students->links() }}
    </div>
</div>

<!-- Payment Collection Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 animate-fade-in" style="border-radius: 20px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header text-white border-0 py-3.5 px-4" style="background: linear-gradient(135deg, var(--sidebar-bg), #1d4ed8);">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">
                    <i class="fa-solid fa-cash-register me-2.5"></i>Collect Arrears Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="paymentForm">
                @csrf
                <input type="hidden" name="student_id" id="modal_student_id">
                
                <!-- Modal Body -->
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <!-- Alert Message Container -->
                    <div id="modalAlert" class="alert d-none shadow-sm" role="alert"></div>

                    <!-- Student Info Box -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2.5">
                                <div>
                                    <span class="text-muted small d-block uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Student Name</span>
                                    <h5 class="fw-bold text-dark mb-0 mt-0.5" id="modal_student_name"></h5>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-3 fw-semibold small" id="modal_student_class_section"></span>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted small d-block"><i class="fa-solid fa-hashtag me-1.5"></i>Roll Number</span>
                                    <span class="fw-bold text-dark fs-6" id="modal_student_roll"></span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="text-muted small d-block"><i class="fa-solid fa-triangle-exclamation me-1.5 text-danger"></i>Total Outstanding</span>
                                    <span class="fw-bold text-danger fs-5 d-block mt-0.5" id="modal_student_total_arrears"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding Month Breakdowns in Modal -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small mb-2 d-flex align-items-center">
                            <i class="fa-solid fa-list-ul me-2"></i>Outstanding Month Breakdown
                        </label>
                        <div class="card border shadow-sm rounded-3">
                            <div id="modal_month_breakdown" class="list-group list-group-flush" style="max-height: 160px; overflow-y: auto;">
                                <!-- List of months loaded by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Payment Inputs -->
                    <div class="row g-3 bg-white p-3.5 rounded-4 shadow-sm border">
                        <!-- Amount to Collect -->
                        <div class="col-12">
                            <label for="amount_to_collect" class="form-label fw-bold text-dark mb-1.5">Amount to Collect (Rs.) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-light fw-bold text-secondary" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px;">Rs.</span>
                                <input type="number" step="0.01" min="0.01" name="amount_to_collect" id="amount_to_collect" class="form-control fw-bold text-primary fs-4" placeholder="0.00" style="outline: none;" required>
                                <button type="button" class="btn btn-primary px-3 text-white fw-bold" id="pay_in_full_btn" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-size: 0.9rem;">Pay in Full</button>
                            </div>
                            <div class="form-text small text-muted mt-1.5"><i class="fa-solid fa-circle-info me-1"></i>Enter the amount. Partial payments are automatically supported.</div>
                        </div>

                        <!-- Date of Collection -->
                        <div class="col-6">
                            <label for="payment_date" class="form-label fw-semibold text-secondary small mb-1">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-6">
                            <label for="payment_method" class="form-label fw-semibold text-secondary small mb-1">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select rounded-3" required>
                                <option value="Cash" selected>Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Check">Check</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top bg-light p-3.5" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                    <button type="button" class="btn btn-outline-secondary px-3.5 py-2 fw-semibold rounded-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success px-4.5 py-2 fw-bold text-white rounded-3 shadow" id="submitPaymentBtn">
                        <span class="spinner-border spinner-border-sm d-none me-1.5" role="status" aria-hidden="true"></span>
                        <i class="fa-solid fa-circle-check me-1.5"></i>Collect Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModalEl = document.getElementById('paymentModal');
    const paymentModal = new bootstrap.Modal(paymentModalEl);
    const paymentForm = document.getElementById('paymentForm');
    
    const alertContainer = document.getElementById('modalAlert');
    const submitBtn = document.getElementById('submitPaymentBtn');
    const spinner = submitBtn.querySelector('.spinner-border');

    let currentStudentMaxArrears = 0;

    // Attach click events to Collect Payment buttons
    document.querySelectorAll('.collect-payment-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Retrieve data attributes
            const id = this.getAttribute('data-student-id');
            const name = this.getAttribute('data-student-name');
            const roll = this.getAttribute('data-student-roll');
            const className = this.getAttribute('data-student-class');
            const section = this.getAttribute('data-student-section');
            const arrears = parseFloat(this.getAttribute('data-student-arrears'));
            const months = JSON.parse(this.getAttribute('data-student-months') || '[]');

            currentStudentMaxArrears = arrears;

            // Populate Modal Static Information
            document.getElementById('modal_student_id').value = id;
            document.getElementById('modal_student_name').textContent = name;
            document.getElementById('modal_student_class_section').textContent = `${className} - Sec ${section}`;
            document.getElementById('modal_student_roll').textContent = roll;
            document.getElementById('modal_student_total_arrears').textContent = `Rs. ${arrears.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

            // Populate Inputs
            const amountInput = document.getElementById('amount_to_collect');
            amountInput.value = arrears.toFixed(2);
            amountInput.setAttribute('max', arrears.toFixed(2));
            
            // Set payment date back to today
            document.getElementById('payment_date').value = new Date().toISOString().substring(0, 10);

            // Populate Outstanding Month Breakdown List
            const breakdownContainer = document.getElementById('modal_month_breakdown');
            breakdownContainer.innerHTML = '';
            
            if (months.length === 0) {
                breakdownContainer.innerHTML = '<div class="text-muted small text-center p-3"><i class="fa-solid fa-circle-info me-1.5"></i>No month-wise details available.</div>';
            } else {
                months.forEach(item => {
                    const parts = item.month.split('-');
                    const year = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1;
                    const dateObj = new Date(year, month, 1);
                    const monthLabel = dateObj.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                    const badgeClass = item.payment_status === 'partially_paid' ? 'bg-warning-subtle text-warning border-warning-subtle' : 'bg-danger-subtle text-danger border-danger-subtle';
                    const badgeLabel = item.payment_status === 'partially_paid' ? 'Partial' : 'Unpaid';
                    
                    const row = document.createElement('div');
                    row.className = 'list-group-item d-flex align-items-center justify-content-between py-2 px-3';
                    row.style.fontSize = '0.825rem';
                    row.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-regular fa-calendar text-secondary" style="font-size: 0.9rem;"></i>
                            <span class="fw-semibold text-dark">${monthLabel}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge ${badgeClass} border py-1 px-2" style="font-size: 0.65rem;">${badgeLabel}</span>
                            <span class="fw-bold text-danger">Rs. ${parseFloat(item.amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>
                    `;
                    breakdownContainer.appendChild(row);
                });
            }

            // Reset alert container
            alertContainer.className = 'alert d-none';
            alertContainer.innerHTML = '';

            // Show modal
            paymentModal.show();
        });
    });

    // Pay In Full Button handler
    document.getElementById('pay_in_full_btn').addEventListener('click', function() {
        document.getElementById('amount_to_collect').value = currentStudentMaxArrears.toFixed(2);
    });

    // Handle form submit via AJAX
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic Client-side Validation
        const amountVal = parseFloat(document.getElementById('amount_to_collect').value);
        if (isNaN(amountVal) || amountVal <= 0) {
            showAlert('danger', 'Please enter a valid amount greater than 0.');
            return;
        }

        if (amountVal > currentStudentMaxArrears) {
            showAlert('danger', `Amount cannot exceed the total outstanding arrears (Rs. ${currentStudentMaxArrears.toFixed(2)}).`);
            return;
        }

        // Show spinner and disable button
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(paymentForm);

        fetch("{{ route('arrears.collect') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');

            if (res.status === 200 && res.body.success) {
                let monthsHtml = '';
                if (res.body.allocated_months && res.body.allocated_months.length > 0) {
                    monthsHtml += '<div class="mt-2 text-start w-100 bg-light p-2.5 rounded-3 border small" style="font-size: 0.8rem;">';
                    monthsHtml += '<div class="fw-bold text-secondary mb-1.5"><i class="fa-solid fa-calendar-check me-1.5"></i>Arrears Allocation Details:</div>';
                    res.body.allocated_months.forEach(item => {
                        let statusText = item.status === 'paid' ? 'Fully Paid' : 'Partially Paid';
                        let statusClass = item.status === 'paid' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning border-warning-subtle';
                        monthsHtml += `
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                <span class="fw-semibold text-dark">${item.label}</span>
                                <span>
                                    <span class="badge ${statusClass} border px-1.5 py-0.5 me-2" style="font-size: 0.65rem;">${statusText}</span>
                                    <strong class="text-primary">Rs. ${parseFloat(item.allocated).toFixed(2)}</strong>
                                </span>
                            </div>
                        `;
                    });
                    monthsHtml += '</div>';
                }

                showAlert('success', `
                    <div class="d-flex flex-column gap-2 align-items-center text-center">
                        <i class="fa-solid fa-circle-check fs-3 text-success"></i>
                        <div><strong>${res.body.message}</strong></div>
                        <div class="text-muted small">Total Collected: Rs. ${amountVal.toFixed(2)} | Remaining: Rs. ${res.body.remaining_arrears.toFixed(2)}</div>
                        ${monthsHtml}
                        <div class="mt-3 w-100 d-flex gap-2 justify-content-center">
                            <a href="{{ url('/receipts') }}/${res.body.receipt_id}" class="btn btn-sm btn-primary px-3" target="_blank"><i class="fa-solid fa-eye me-1"></i>View Receipt</a>
                            <a href="{{ url('/receipts') }}/${res.body.receipt_id}/pdf" class="btn btn-sm btn-secondary px-3" target="_blank"><i class="fa-solid fa-file-pdf me-1"></i>PDF Receipt</a>
                        </div>
                        <hr class="w-100 my-2">
                        <div class="text-muted small" style="font-size: 0.75rem;">
                            Auto-refreshing in 15 seconds to update records, or click below:
                        </div>
                        <button onclick="location.reload()" class="btn btn-sm btn-outline-success px-3 w-100"><i class="fa-solid fa-arrows-rotate me-1"></i>Refresh Page Now</button>
                    </div>
                `);
                
                // Hide submit button to prevent double-payment
                submitBtn.classList.add('d-none');

                // Refresh the page after 15 seconds to display the updated table
                setTimeout(() => {
                    location.reload();
                }, 15000);
            } else {
                showAlert('danger', res.body.message || 'An error occurred while processing the payment.');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            showAlert('danger', 'Failed to submit payment. Please verify your connection.');
            console.error('Error:', error);
        });
    });

    // Helper function to show alert in modal
    function showAlert(type, html) {
        alertContainer.className = `alert alert-${type} p-3 mb-3`;
        alertContainer.innerHTML = html;
        alertContainer.classList.remove('d-none');
    }
});
</script>
@endsection
