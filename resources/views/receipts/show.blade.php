@extends('layouts.app')

@section('title', 'Receipt #' . $receipt->receipt_number)

@section('styles')
<style>
    .info-table-web th, .info-table-web td {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
    
    @page {
        size: A4 portrait;
        margin: 8mm 10mm;
    }

    @media print {
        /* Hide navbar, sidebar, buttons, header */
        .top-header, 
        #sidebar, 
        .page-title-box,
        aside,
        nav,
        header,
        .d-print-none {
            display: none !important;
        }

        /* Reset main wrappers */
        html,
        body,
        #wrapper,
        #content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            background: #ffffff !important;
            display: block !important;
            min-height: auto !important;
            position: relative !important;
            font-size: 8px !important;
            line-height: 1.2 !important;
        }

        /* Custom print sizes for cards */
        .card-box {
            padding: 10px !important;
            margin: 0 0 4px 0 !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            border-radius: 6px !important;
            page-break-inside: avoid !important;
        }

        /* Shrink title and details */
        h3.text-primary {
            font-size: 11px !important;
            margin-bottom: 1px !important;
        }
        p.text-muted {
            font-size: 7.5px !important;
            margin-bottom: 0 !important;
        }
        .doc-title {
            font-size: 9px !important;
            margin-bottom: 4px !important;
        }
        .row.align-items-center.mb-4 {
            margin-bottom: 6px !important;
            padding-bottom: 4px !important;
        }

        /* Force side-by-side columns on print */
        .col-7 {
            width: 58% !important;
            float: left !important;
            display: inline-block !important;
        }
        
        .col-5 {
            width: 40% !important;
            float: right !important;
            display: inline-block !important;
        }

        .row {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Shrink student info table */
        .info-table-web td {
            padding: 3px 5px !important;
            font-size: 8px !important;
        }
        .table-responsive {
            margin-bottom: 6px !important;
            overflow: visible !important;
        }

        /* Shrink internal tables and spacing */
        .table {
            margin-bottom: 3px !important;
        }
        .table th, .table td {
            padding: 2px 4px !important;
            font-size: 7.5px !important;
        }

        .card {
            margin-bottom: 4px !important;
        }
        .card-header {
            padding: 3px 5px !important;
            font-size: 8px !important;
        }
        .card-body {
            padding: 4px !important;
        }

        /* Transaction box */
        .bg-success-subtle {
            padding: 6px !important;
        }
        .fs-4 {
            font-size: 0.95rem !important;
        }
        .fs-5 {
            font-size: 0.8rem !important;
        }

        /* List group ledger items */
        .list-group-item {
            padding: 2px 4px !important;
            font-size: 7.5px !important;
        }

        /* Signature block */
        .mt-4 {
            margin-top: 10px !important;
        }
        .row.mt-4.pt-3 {
            margin-top: 10px !important;
            padding-top: 4px !important;
        }

        /* Force colors in print dialog */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-title-box d-print-none">
    <div>
        <h3>Payment Receipt</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $receipt->receipt_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <button onclick="window.print();" class="btn btn-outline-primary me-2"><i class="fa-solid fa-print me-2"></i>Print Receipt</button>
        <a href="{{ route('receipts.pdf', $receipt->id) }}" class="btn btn-danger me-2"><i class="fa-solid fa-file-pdf me-2"></i>Download PDF</a>
        <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
</div>

@php
    $arrearsDetails = [];
    if($receipt->arrears_months_details) {
        $arrearsDetails = json_decode($receipt->arrears_months_details, true) ?: [];
    }
    $totalArrearsPaid = array_sum(array_column($arrearsDetails, 'allocated'));
    
    $studentPendingArrears = \App\Models\StudentArrear::where('student_id', $receipt->student_id)
        ->whereIn('payment_status', ['unpaid', 'partially_paid'])
        ->orderBy('month', 'asc')
        ->get();
@endphp

<!-- RECEIPT SHEET CONTAINER -->
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        
        <!-- ==================== 1st COPY: SCHOOL COPY (Visible on Web & Print) ==================== -->
        <div class="card-box p-4 border shadow-sm bg-white" style="border-radius: 12px; margin-top: 10px; position: relative;">
            <div class="d-none d-print-block font-monospace fw-bold text-danger border border-danger px-2.5 py-1 rounded" style="position: absolute; top: 25px; right: 25px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">School Copy</div>
            
            <!-- School Header -->
            <div class="row align-items-center mb-4 pb-3 border-bottom">
                <div class="col-8">
                    <h3 class="text-primary fw-bold mb-1 uppercase" style="font-family:'Outfit', sans-serif; letter-spacing: 0.3px;">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h3>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-location-dot me-1"></i> {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                        <i class="fa-solid fa-phone me-1"></i> {{ $school->phone ?? '0300-1234567' }} | <i class="fa-solid fa-envelope me-1"></i> {{ $school->email ?? 'info@superdawnschool.edu.pk' }}
                    </p>
                </div>
                <div class="col-4 text-end">
                    <div class="p-2 border rounded bg-light d-inline-block text-start">
                        <div class="small text-muted font-monospace" style="font-size:0.7rem;">RECEIPT NO.</div>
                        <div class="fw-bold text-dark font-monospace fs-6">{{ $receipt->receipt_number }}</div>
                    </div>
                </div>
            </div>

            <!-- Student details structured grid table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle info-table-web mb-0">
                    <tbody>
                        <tr>
                            <td class="bg-light text-secondary fw-bold" style="width: 18%;">Student Name</td>
                            <td class="fw-bold text-dark" style="width: 32%;">{{ $receipt->student->name }}</td>
                            <td class="bg-light text-secondary fw-bold" style="width: 18%;">Receipt Date</td>
                            <td class="fw-bold text-dark" style="width: 32%;">{{ $receipt->date->format('d-M-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light text-secondary fw-bold">Father Name</td>
                            <td class="fw-semibold text-dark">{{ $receipt->student->father_name }}</td>
                            <td class="bg-light text-secondary fw-bold">Academic Session</td>
                            <td class="fw-semibold text-dark">{{ $school->academic_session ?? '2026-2027' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light text-secondary fw-bold">Class & Section</td>
                            <td class="fw-bold text-primary">{{ $receipt->student->class->name }} - {{ $receipt->student->section ?? 'A' }}</td>
                            <td class="bg-light text-secondary fw-bold">Roll Number</td>
                            <td class="fw-semibold text-dark">{{ $receipt->student->roll_number }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Fee Breakdown Section -->
            <div class="row g-3 mb-4">
                <div class="col-7">
                    <div class="card border shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-light py-2.5 fw-bold text-dark small"><i class="fa-solid fa-receipt me-2 text-primary"></i>Current Fees collected in this transaction</div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-borderless align-middle small mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Admission Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Monthly Tuition Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Exam Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td class="text-dark py-2">Total Current Fee</td>
                                        <td class="text-end text-dark fs-6">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card border border-warning shadow-sm rounded-3">
                        <div class="card-header bg-warning-subtle text-warning-emphasis py-2.5 fw-bold small d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-calendar-check me-2"></i>Arrears Payments Collected</span>
                            <span class="badge bg-warning text-warning-emphasis">Collected: Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                        </div>
                        <div class="card-body p-3">
                            @if(count($arrearsDetails) > 0)
                                <table class="table table-sm table-borderless align-middle small mb-0">
                                    <thead>
                                        <tr class="border-bottom text-muted">
                                            <th class="text-start pb-2">Arrears Month</th>
                                            <th class="text-center pb-2">Allocation Status</th>
                                            <th class="text-end pb-2">Collected Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($arrearsDetails as $item)
                                            <tr class="border-bottom">
                                                <td class="fw-semibold text-dark py-2.5">{{ $item['label'] }}</td>
                                                <td class="text-center py-2.5">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.7rem;">
                                                        {{ $item['status'] === 'paid' ? 'Fully Paid' : 'Partially Paid' }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold text-primary py-2.5">Rs. {{ number_format($item['allocated'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-muted text-center py-3 small">
                                    <i class="fa-solid fa-circle-info me-1.5"></i>No outstanding arrears were paid in this transaction.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    <div class="card border border-success shadow-sm rounded-3 mb-4 bg-success-subtle bg-opacity-10">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-success border-bottom border-success pb-2 mb-3"><i class="fa-solid fa-circle-check me-2"></i>Transaction Complete</h6>
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span>Total Current Fee:</span>
                                <span>Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 small text-muted">
                                <span>Total Arrears Collected:</span>
                                <span>Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top border-success-subtle pt-2.5 mb-0">
                                <span class="fw-bold text-dark fs-5">Grand Total Received:</span>
                                <span class="fw-bold text-success fs-4">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card border shadow-sm rounded-3">
                        <div class="card-header bg-light py-2.5 fw-bold text-dark small"><i class="fa-solid fa-scale-unbalanced me-2 text-warning"></i>Outstanding Arrears Ledger</div>
                        <div class="card-body p-3 small">
                            <div class="mb-3">
                                <span class="text-secondary fw-semibold d-block mb-1.5">Previously Outstanding Months:</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $prevMonths = [];
                                        foreach($arrearsDetails as $det) {
                                            $prevMonths[] = $det['label'];
                                        }
                                        foreach($studentPendingArrears as $pend) {
                                            $mLabel = date('F Y', strtotime($pend->month . '-01'));
                                            if(!in_array($mLabel, $prevMonths)) {
                                                $prevMonths[] = $mLabel;
                                            }
                                        }
                                    @endphp
                                    @forelse($prevMonths as $pm)
                                        <span class="badge bg-light text-dark border px-2 py-1.5">{{ $pm }}</span>
                                    @empty
                                        <span class="text-muted small">None.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <span class="text-secondary fw-semibold d-block mb-1.5">Remaining Pending Arrears:</span>
                                <ul class="list-group list-group-flush border rounded-3 overflow-hidden mb-0">
                                    @forelse($studentPendingArrears as $pending)
                                        @php
                                            $pendingLabel = date('F Y', strtotime($pending->month . '-01'));
                                            $badgeClass = $pending->payment_status === 'partially_paid' ? 'bg-warning-subtle text-warning border-warning-subtle' : 'bg-danger-subtle text-danger border-danger-subtle';
                                            $badgeLabel = $pending->payment_status === 'partially_paid' ? 'Partially Paid' : 'Unpaid';
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-2.5">
                                            <span>
                                                <span class="fw-semibold text-dark">{{ $pendingLabel }}</span>
                                                <span class="badge {{ $badgeClass }} border ms-1 px-1.5 py-0.5" style="font-size: 0.6rem;">{{ $badgeLabel }}</span>
                                            </span>
                                            <span class="fw-bold text-danger">Rs. {{ number_format($pending->amount, 2) }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center py-3 text-success fw-bold">
                                            <i class="fa-solid fa-circle-check me-1.5"></i>No outstanding arrears!
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature footer area -->
            <div class="row mt-4 pt-3 text-center small text-muted">
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Class Teacher Signature
                </div>
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Principal Signature<br>
                    ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                </div>
            </div>

        </div>

        <!-- ==================== CUT LINE (Printed only) ==================== -->
        <div class="d-none d-print-block text-center my-4" style="border-top: 1px dashed #777; position: relative; height: 1px; width: 100%;">
            <span style="font-size: 9px; color: #555; background: #ffffff; padding: 0 10px; position: relative; top: -10px; font-family: monospace; letter-spacing: 1px;">✂------------------ CUT HERE ------------------✂</span>
        </div>

        <!-- ==================== 2nd COPY: PARENT COPY (Printed only) ==================== -->
        <div class="card-box p-4 border shadow-sm bg-white d-none d-print-block" style="border-radius: 12px; margin-top: 10px; position: relative;">
            <div class="font-monospace fw-bold text-primary border border-primary px-2.5 py-1 rounded" style="position: absolute; top: 25px; right: 25px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Parent Copy</div>
            
            <!-- School Header -->
            <div class="row align-items-center mb-4 pb-3 border-bottom">
                <div class="col-8">
                    <h3 class="text-primary fw-bold mb-1 uppercase" style="font-family:'Outfit', sans-serif; letter-spacing: 0.3px;">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h3>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-location-dot me-1"></i> {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                        <i class="fa-solid fa-phone me-1"></i> {{ $school->phone ?? '0300-1234567' }} | <i class="fa-solid fa-envelope me-1"></i> {{ $school->email ?? 'info@superdawnschool.edu.pk' }}
                    </p>
                </div>
                <div class="col-4 text-end">
                    <div class="p-2 border rounded bg-light d-inline-block text-start">
                        <div class="small text-muted font-monospace" style="font-size:0.7rem;">RECEIPT NO.</div>
                        <div class="fw-bold text-dark font-monospace fs-6">{{ $receipt->receipt_number }}</div>
                    </div>
                </div>
            </div>

            <!-- Student details structured grid table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle info-table-web mb-0">
                    <tbody>
                        <tr>
                            <td class="bg-light text-secondary fw-bold" style="width: 18%;">Student Name</td>
                            <td class="fw-bold text-dark" style="width: 32%;">{{ $receipt->student->name }}</td>
                            <td class="bg-light text-secondary fw-bold" style="width: 18%;">Receipt Date</td>
                            <td class="fw-bold text-dark" style="width: 32%;">{{ $receipt->date->format('d-M-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light text-secondary fw-bold">Father Name</td>
                            <td class="fw-semibold text-dark">{{ $receipt->student->father_name }}</td>
                            <td class="bg-light text-secondary fw-bold">Academic Session</td>
                            <td class="fw-semibold text-dark">{{ $school->academic_session ?? '2026-2027' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light text-secondary fw-bold">Class & Section</td>
                            <td class="fw-bold text-primary">{{ $receipt->student->class->name }} - {{ $receipt->student->section ?? 'A' }}</td>
                            <td class="bg-light text-secondary fw-bold">Roll Number</td>
                            <td class="fw-semibold text-dark">{{ $receipt->student->roll_number }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Fee Breakdown Section -->
            <div class="row g-3 mb-4">
                <div class="col-7">
                    <div class="card border shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-light py-2.5 fw-bold text-dark small"><i class="fa-solid fa-receipt me-2 text-primary"></i>Current Fees collected in this transaction</div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-borderless align-middle small mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Admission Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Monthly Tuition Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted py-2">Exam Fee</td>
                                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td class="text-dark py-2">Total Current Fee</td>
                                        <td class="text-end text-dark fs-6">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card border border-warning shadow-sm rounded-3">
                        <div class="card-header bg-warning-subtle text-warning-emphasis py-2.5 fw-bold small d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-calendar-check me-2"></i>Arrears Payments Collected</span>
                            <span class="badge bg-warning text-warning-emphasis">Collected: Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                        </div>
                        <div class="card-body p-3">
                            @if(count($arrearsDetails) > 0)
                                <table class="table table-sm table-borderless align-middle small mb-0">
                                    <thead>
                                        <tr class="border-bottom text-muted">
                                            <th class="text-start pb-2">Arrears Month</th>
                                            <th class="text-center pb-2">Allocation Status</th>
                                            <th class="text-end pb-2">Collected Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($arrearsDetails as $item)
                                            <tr class="border-bottom">
                                                <td class="fw-semibold text-dark py-2.5">{{ $item['label'] }}</td>
                                                <td class="text-center py-2.5">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.7rem;">
                                                        {{ $item['status'] === 'paid' ? 'Fully Paid' : 'Partially Paid' }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold text-primary py-2.5">Rs. {{ number_format($item['allocated'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-muted text-center py-3 small">
                                    <i class="fa-solid fa-circle-info me-1.5"></i>No outstanding arrears were paid in this transaction.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    <div class="card border border-success shadow-sm rounded-3 mb-4 bg-success-subtle bg-opacity-10">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-success border-bottom border-success pb-2 mb-3"><i class="fa-solid fa-circle-check me-2"></i>Transaction Complete</h6>
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span>Total Current Fee:</span>
                                <span>Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 small text-muted">
                                <span>Total Arrears Collected:</span>
                                <span>Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top border-success-subtle pt-2.5 mb-0">
                                <span class="fw-bold text-dark fs-5">Grand Total Received:</span>
                                <span class="fw-bold text-success fs-4">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card border shadow-sm rounded-3">
                        <div class="card-header bg-light py-2.5 fw-bold text-dark small"><i class="fa-solid fa-scale-unbalanced me-2 text-warning"></i>Outstanding Arrears Ledger</div>
                        <div class="card-body p-3 small">
                            <div class="mb-3">
                                <span class="text-secondary fw-semibold d-block mb-1.5">Previously Outstanding Months:</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $prevMonths = [];
                                        foreach($arrearsDetails as $det) {
                                            $prevMonths[] = $det['label'];
                                        }
                                        foreach($studentPendingArrears as $pend) {
                                            $mLabel = date('F Y', strtotime($pend->month . '-01'));
                                            if(!in_array($mLabel, $prevMonths)) {
                                                $prevMonths[] = $mLabel;
                                            }
                                        }
                                    @endphp
                                    @forelse($prevMonths as $pm)
                                        <span class="badge bg-light text-dark border px-2 py-1.5">{{ $pm }}</span>
                                    @empty
                                        <span class="text-muted small">None.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <span class="text-secondary fw-semibold d-block mb-1.5">Remaining Pending Arrears:</span>
                                <ul class="list-group list-group-flush border rounded-3 overflow-hidden mb-0">
                                    @forelse($studentPendingArrears as $pending)
                                        @php
                                            $pendingLabel = date('F Y', strtotime($pending->month . '-01'));
                                            $badgeClass = $pending->payment_status === 'partially_paid' ? 'bg-warning-subtle text-warning border-warning-subtle' : 'bg-danger-subtle text-danger border-danger-subtle';
                                            $badgeLabel = $pending->payment_status === 'partially_paid' ? 'Partially Paid' : 'Unpaid';
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-2.5">
                                            <span>
                                                <span class="fw-semibold text-dark">{{ $pendingLabel }}</span>
                                                <span class="badge {{ $badgeClass }} border ms-1 px-1.5 py-0.5" style="font-size: 0.6rem;">{{ $badgeLabel }}</span>
                                            </span>
                                            <span class="fw-bold text-danger">Rs. {{ number_format($pending->amount, 2) }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center py-3 text-success fw-bold">
                                            <i class="fa-solid fa-circle-check me-1.5"></i>No outstanding arrears!
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature footer area -->
            <div class="row mt-4 pt-3 text-center small text-muted">
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Class Teacher Signature
                </div>
                <div class="col-6">
                    <div style="width: 160px; border-bottom: 1px solid #ddd; margin: 0 auto 5px auto;"></div>
                    Principal Signature<br>
                    ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
