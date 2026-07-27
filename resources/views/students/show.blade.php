@extends('layouts.app')

@section('title', 'Student Profile - ' . $student->name)

@section('content')
<div class="page-title-box">
    <div>
        <h3>Student Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $student->name }}</li>
            </ol>
        </nav>
    </div>
    
    <div class="text-end d-flex gap-2">
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary"><i class="fa-solid fa-user-pen me-2"></i>Edit Profile</a>
        
        <!-- Toggle Status forms (no student DELETE route) -->
        @if($student->status === 'active')
            <form action="{{ route('students.deactivate', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this student? All history will be preserved.');">
                @csrf
                <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-user-slash me-2"></i>Deactivate Student</button>
            </form>
        @else
            <form action="{{ route('students.reactivate', $student->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-user-check me-2"></i>Reactivate Student</button>
            </form>
        @endif
        
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
</div>

<div class="row">
    <!-- Left Column: Student Details Card (Col 4) -->
    <div class="col-12 col-lg-4 mb-4">
        <div class="card-box text-center">
            @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="profile-avatar-large mb-3">
            @else
                <div class="mx-auto rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center mb-3" style="width: 130px; height: 130px; font-size: 3rem; font-weight:700;">
                    {{ substr($student->name, 0, 1) }}
                </div>
            @endif
            <h4 class="mb-1 text-dark">{{ $student->name }}</h4>
            <div class="text-muted mb-2">Admission No: <code class="text-dark fw-bold fs-6">{{ $student->admission_number }}</code></div>
            
            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge bg-light text-primary border px-3 py-2">Class: {{ $student->class->name }}</span>
                <span class="badge bg-light text-dark border px-3 py-2">Roll: {{ $student->roll_number }}</span>
            </div>

            <div class="mb-3">
                @if($student->status === 'active')
                    <span class="badge-status badge-status-active px-3 py-2">ACTIVE</span>
                @else
                    <span class="badge-status badge-status-inactive px-3 py-2">INACTIVE (LEFT)</span>
                @endif
            </div>

            <div class="card-box bg-light border-0 shadow-none text-start p-3 mt-4">
                <div class="fw-semibold text-secondary mb-2 small uppercase"><i class="fa-solid fa-circle-info me-1"></i>Contact Information</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Father:</strong> {{ $student->father_name }}</div>
                <div class="small text-muted mb-2"><strong class="text-dark">DOB:</strong> {{ $student->date_of_birth->format('d-M-Y') }}</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Gender:</strong> {{ ucfirst($student->gender) }}</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Phone:</strong> {{ $student->phone ?? '-' }}</div>
                <div class="small text-muted"><strong class="text-dark">Address:</strong> {{ $student->address }}</div>
            </div>

            <div class="card-box bg-light border-0 shadow-none text-start p-3 mt-3">
                <div class="fw-semibold text-secondary mb-2 small uppercase"><i class="fa-solid fa-wallet me-1"></i>Fee Account</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Monthly Fee:</strong> Rs. {{ number_format($student->monthly_fee, 2) }}</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Admission Fee:</strong> Rs. {{ number_format($student->admission_fee, 2) }}</div>
                <div class="small text-muted mb-2"><strong class="text-dark">Exam Fee:</strong> Rs. {{ number_format($student->exam_fee, 2) }}</div>
                <div class="small text-muted"><strong class="text-dark">Outstanding Arrears:</strong> <span class="text-warning fw-semibold">Rs. {{ number_format($student->arrears, 2) }}</span></div>
            </div>
        </div>
    </div>

    <!-- Right Column: History Tabs (Col 8) -->
    <div class="col-12 col-lg-8">
        <div class="card-box">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs nav-tabs-custom mb-3" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ledger-tab" data-bs-toggle="tab" data-bs-target="#ledger" type="button" role="tab" aria-controls="ledger" aria-selected="true">
                        <i class="fa-solid fa-receipt me-1"></i>Fee Ledger
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="receipts-tab" data-bs-toggle="tab" data-bs-target="#receipts" type="button" role="tab" aria-controls="receipts" aria-selected="false">
                        <i class="fa-solid fa-wallet me-1"></i>Receipts History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="academics-tab" data-bs-toggle="tab" data-bs-target="#academics" type="button" role="tab" aria-controls="academics" aria-selected="false">
                        <i class="fa-solid fa-id-card-clip me-1"></i>Marksheets
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="admissions-tab" data-bs-toggle="tab" data-bs-target="#admissions" type="button" role="tab" aria-controls="admissions" aria-selected="false">
                        <i class="fa-solid fa-user-plus me-1"></i>Admission History
                    </button>
                </li>
            </ul>

            <!-- Tab Contents -->
            <div class="tab-content" id="profileTabsContent">
                
                <!-- 1. FEE LEDGER -->
                <div class="tab-pane fade show active" id="ledger" role="tabpanel" aria-labelledby="ledger-tab">
                    <h5 class="text-secondary mb-3 small uppercase">Fee Ledger Transaction History</h5>
                    <div class="table-responsive">
                        <table class="table table-custom text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Receipt No</th>
                                    <th>Adm Fee</th>
                                    <th>Monthly</th>
                                    <th>Exam Fee</th>
                                    <th>Prev Arrears</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->feeTransactions as $tx)
                                    <tr>
                                        <td>{{ $tx->date->format('d-M-Y') }}</td>
                                        <td><code class="text-dark">{{ $tx->receipt_number }}</code></td>
                                        <td>Rs. {{ number_format($tx->admission_fee, 0) }}</td>
                                        <td>Rs. {{ number_format($tx->monthly_fee, 0) }}</td>
                                        <td>Rs. {{ number_format($tx->exam_fee, 0) }}</td>
                                        <td>Rs. {{ number_format($tx->previous_arrears, 0) }}</td>
                                        <td class="text-success fw-semibold">Rs. {{ number_format($tx->paid_amount, 0) }}</td>
                                        <td class="text-warning fw-semibold">Rs. {{ number_format($tx->remaining_arrears, 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No fee transaction ledger found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. RECEIPTS HISTORY -->
                <div class="tab-pane fade" id="receipts" role="tabpanel" aria-labelledby="receipts-tab">
                    <h5 class="text-secondary mb-3 small uppercase">Receipts List</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Payment Date</th>
                                    <th class="text-end">Paid Amount</th>
                                    <th class="text-end">Balance Arrears</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->feeReceipts as $rec)
                                    <tr>
                                        <td><code class="text-dark fw-bold">{{ $rec->receipt_number }}</code></td>
                                        <td>{{ $rec->date->format('d-M-Y') }}</td>
                                        <td class="text-end text-success fw-semibold">Rs. {{ number_format($rec->paid_amount, 2) }}</td>
                                        <td class="text-end text-warning">Rs. {{ number_format($rec->remaining_arrears, 2) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('receipts.show', $rec->id) }}" class="btn btn-outline-info btn-sm me-1">View</a>
                                            <a href="{{ route('receipts.pdf', $rec->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-file-pdf text-danger"></i> PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No receipts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. ACADEMIC MARKSHEETS -->
                <div class="tab-pane fade" id="academics" role="tabpanel" aria-labelledby="academics-tab">
                    <h5 class="text-secondary mb-3 small uppercase">Generated Academic Marksheets</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Session</th>
                                    <th class="text-center">Total Marks</th>
                                    <th class="text-center">Obtained Marks</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">Grade</th>
                                    <th>Result</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->marksheets as $ms)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $ms->exam->name }}</span></td>
                                        <td><code>{{ $ms->academic_session }}</code></td>
                                        <td class="text-center">{{ $ms->total_marks }}</td>
                                        <td class="text-center fw-medium">{{ $ms->obtained_marks }}</td>
                                        <td class="text-center fw-semibold">{{ $ms->percentage }}%</td>
                                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $ms->grade }}</span></td>
                                        <td>
                                            @if($ms->result === 'PASS')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">PASS</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">FAIL</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('marksheets.show', $ms->id) }}" class="btn btn-outline-info btn-sm me-1">View</a>
                                            <a href="{{ route('marksheets.pdf', $ms->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-file-pdf text-danger"></i> PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No academic marksheets generated yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. ADMISSION HISTORY -->
                <div class="tab-pane fade" id="admissions" role="tabpanel" aria-labelledby="admissions-tab">
                    <h5 class="text-secondary mb-3 small uppercase">Admission Application Logs</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Class Enrolled</th>
                                    <th>Admission Date</th>
                                    <th class="text-end">Admission Fee</th>
                                    <th class="text-end">Monthly Fee</th>
                                    <th class="text-end">Exam Fee</th>
                                    <th class="text-end">Initial Arrears</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->admissions as $adm)
                                    <tr>
                                        <td><span class="badge bg-light text-primary border">{{ $adm->class->name }}</span></td>
                                        <td>{{ $adm->admission_date->format('d-M-Y') }}</td>
                                        <td class="text-end">Rs. {{ number_format($adm->admission_fee, 2) }}</td>
                                        <td class="text-end">Rs. {{ number_format($adm->monthly_fee, 2) }}</td>
                                        <td class="text-end">Rs. {{ number_format($adm->exam_fee, 2) }}</td>
                                        <td class="text-end text-warning">Rs. {{ number_format($adm->arrears, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No admissions log found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
