@extends('layouts.app')

@section('title', 'System Reports')

@section('content')
<div class="page-title-box">
    <div>
        <h3>System Reports</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Left Column: Report Selectors (Col 4) -->
    <div class="col-12 col-lg-4 mb-4">
        <div class="card-box">
            <h5 class="text-primary mb-3"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Report Generator</h5>
            
            <form action="{{ route('reports.generate') }}" method="GET" id="reportForm">
                <!-- 1. Main Category -->
                <div class="mb-3">
                    <label for="report_type" class="form-label">Report Category</label>
                    <select name="report_type" id="report_type" class="form-select" required>
                        <option value="">Select Category...</option>
                        <option value="student" {{ ($reportType ?? '') === 'student' ? 'selected' : '' }}>Student Directory Reports</option>
                        <option value="fee" {{ ($reportType ?? '') === 'fee' ? 'selected' : '' }}>Financial & Fee Reports</option>
                        <option value="academic" {{ ($reportType ?? '') === 'academic' ? 'selected' : '' }}>Academic & Exam Reports</option>
                    </select>
                </div>

                <!-- 2. Sub-Category -->
                <div class="mb-3">
                    <label for="sub_type" class="form-label">Report Sub-Type</label>
                    <select name="sub_type" id="sub_type" class="form-select" required disabled>
                        <option value="">Choose category first...</option>
                    </select>
                </div>

                <!-- 3. Dynamic Filters Block -->
                <div id="dynamic-filters">
                    
                    <!-- Class Filter (All categories) -->
                    <div class="mb-3 filter-item d-none" id="filter-class">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">All Classes</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ ($filters['class_id'] ?? '') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Filter (Fee ledger/receipt lists) -->
                    <div class="mb-3 filter-item d-none" id="filter-student">
                        <label for="student_id" class="form-label">Student</label>
                        <select name="student_id" id="student_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}" {{ ($filters['student_id'] ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exam Filter (Academic result) -->
                    <div class="mb-3 filter-item d-none" id="filter-exam">
                        <label for="exam_id" class="form-label">Exam Session</label>
                        <select name="exam_id" id="exam_id" class="form-select">
                            <option value="">Select Exam...</option>
                            @foreach($exams as $ex)
                                <option value="{{ $ex->id }}" {{ ($filters['exam_id'] ?? '') == $ex->id ? 'selected' : '' }}>{{ $ex->name }} ({{ $ex->academic_session }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date scope (Daily collection) -->
                    <div class="mb-3 filter-item d-none" id="filter-date">
                        <label for="date" class="form-label">Select Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $filters['date'] ?? date('Y-m-d') }}">
                    </div>

                    <!-- Month & Year scope (Monthly collections) -->
                    <div class="row g-2 mb-3 filter-item d-none" id="filter-month-year">
                        <div class="col-6">
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ ($filters['month'] ?? date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,10)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-select">
                                @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}" {{ ($filters['year'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Date Range scope -->
                    <div class="row g-2 mb-3 filter-item d-none" id="filter-range">
                        <div class="col-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Export Format selection -->
                <input type="hidden" name="format" id="export_format" value="html">

                <div class="row g-2 mt-2">
                    <div class="col-6">
                        <button type="submit" onclick="document.getElementById('export_format').value='html';" class="btn btn-primary w-100"><i class="fa-solid fa-list-check me-2"></i>Generate</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" onclick="document.getElementById('export_format').value='pdf';" class="btn btn-danger w-100"><i class="fa-solid fa-file-pdf me-2"></i>PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Report Output View (Col 8) -->
    <div class="col-12 col-lg-8">
        <div class="card-box">
            @if(isset($data))
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="text-primary mb-0"><i class="fa-solid fa-chart-line me-2"></i>{{ $title }}</h5>
                    <button onclick="window.print();" class="btn btn-outline-primary btn-sm d-print-none"><i class="fa-solid fa-print me-1"></i>Print Report</button>
                </div>

                <!-- 1. STUDENT REPORT OUTPUT -->
                @if($reportType === 'student')
                    <div class="table-responsive">
                        <table class="table table-custom align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Admission No</th>
                                    <th class="text-start">Name</th>
                                    <th>Father Name</th>
                                    <th>Class</th>
                                    <th>Roll No</th>
                                    <th>Admission Date</th>
                                    <th>Arrears</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $st)
                                    <tr>
                                        <td><code>{{ $st->admission_number }}</code></td>
                                        <td class="text-start fw-semibold"><a href="{{ route('students.show', $st->id) }}">{{ $st->name }}</a></td>
                                        <td>{{ $st->father_name }}</td>
                                        <td>{{ $st->class->name }}</td>
                                        <td>{{ $st->roll_number }}</td>
                                        <td>{{ $st->admission_date->format('d-M-Y') }}</td>
                                        <td class="fw-semibold text-warning">Rs. {{ number_format($st->arrears, 0) }}</td>
                                        <td><span class="badge {{ $st->status==='active' ? 'bg-success' : 'bg-danger' }}">{{ strtoupper($st->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-muted py-4">No student records found matching filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                <!-- 2. FEE REPORT OUTPUT -->
                @elseif($reportType === 'fee')
                    <div class="table-responsive">
                        @if($subType === 'arrears')
                            <table class="table table-custom align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Admission No</th>
                                        <th class="text-start">Student Name</th>
                                        <th>Father Name</th>
                                        <th>Class</th>
                                        <th class="text-end">Outstanding Arrears</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totArr = 0; @endphp
                                    @forelse($data as $st)
                                        @php $totArr += $st->arrears; @endphp
                                        <tr>
                                            <td><code>{{ $st->admission_number }}</code></td>
                                            <td class="text-start fw-semibold"><a href="{{ route('students.show', $st->id) }}">{{ $st->name }}</a></td>
                                            <td>{{ $st->father_name }}</td>
                                            <td>{{ $st->class->name }}</td>
                                            <td class="text-end text-warning fw-semibold">Rs. {{ number_format($st->arrears, 2) }}</td>
                                            <td class="text-end"><a href="{{ route('fee-collection.create', ['student_id' => $st->id]) }}" class="btn btn-primary btn-sm py-0.5 px-2">Collect</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-muted py-4">No arrears outstanding! All students are clear.</td></tr>
                                    @endforelse
                                    @if(count($data) > 0)
                                        <tr style="font-weight:bold; background-color:#f8fafc;">
                                            <td colspan="4" class="text-start">Total Outstanding Arrears Balance:</td>
                                            <td class="text-end text-warning">Rs. {{ number_format($totArr, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @elseif($subType === 'class_wise')
                            <table class="table table-custom align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th class="text-end">Admission Fees</th>
                                        <th class="text-end">Monthly Fees</th>
                                        <th class="text-end">Exam Fees</th>
                                        <th class="text-end">Total Collections</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @forelse($data as $c)
                                        @php $grandTotal += $c->total_collected; @endphp
                                        <tr>
                                            <td><span class="fw-semibold text-dark">{{ $c->class_name }}</span></td>
                                            <td class="text-end">Rs. {{ number_format($c->total_admission, 2) }}</td>
                                            <td class="text-end">Rs. {{ number_format($c->total_monthly, 2) }}</td>
                                            <td class="text-end">Rs. {{ number_format($c->total_exam, 2) }}</td>
                                            <td class="text-end fw-bold text-success">Rs. {{ number_format($c->total_collected, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted py-4">No fees transaction history registered.</td></tr>
                                    @endforelse
                                    @if(count($data) > 0)
                                        <tr style="font-weight:bold; background-color:#f8fafc;">
                                            <td class="text-start">Grand Total Collected:</td>
                                            <td colspan="4" class="text-end text-success fs-5">Rs. {{ number_format($grandTotal, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @else
                            <!-- Daily, Monthly, Yearly Collection table -->
                            <table class="table table-custom align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Receipt No</th>
                                        <th>Date</th>
                                        <th class="text-start">Student Name</th>
                                        <th>Class</th>
                                        <th class="text-end">Paid Amount</th>
                                        <th class="text-end">Balance Arrears</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totPaid = 0; @endphp
                                    @forelse($data as $rec)
                                        @php $totPaid += $rec->paid_amount; @endphp
                                        <tr>
                                            <td><code>{{ $rec->receipt_number }}</code></td>
                                            <td>{{ $rec->date->format('d-M-Y') }}</td>
                                            <td class="text-start fw-semibold"><a href="{{ route('students.show', $rec->student->id) }}">{{ $rec->student->name }}</a></td>
                                            <td>{{ $rec->student->class->name }}</td>
                                            <td class="text-end text-success fw-bold">Rs. {{ number_format($rec->paid_amount, 2) }}</td>
                                            <td class="text-end text-warning">Rs. {{ number_format($rec->remaining_arrears, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-muted py-4">No collections recorded for the selected date range.</td></tr>
                                    @endforelse
                                    @if(count($data) > 0)
                                        <tr style="font-weight:bold; background-color:#f8fafc;">
                                            <td colspan="4" class="text-start">Total Collected:</td>
                                            <td class="text-end text-success">Rs. {{ number_format($totPaid, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @endif
                    </div>

                <!-- 3. ACADEMIC REPORT OUTPUT -->
                @elseif($reportType === 'academic')
                    <div class="table-responsive">
                        <table class="table table-custom align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th class="text-start">Student Name</th>
                                    <th>Exam</th>
                                    <th>Total</th>
                                    <th>Obtained</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $ms)
                                    <tr>
                                        <td>{{ $ms->student->roll_number }}</td>
                                        <td class="text-start fw-semibold"><a href="{{ route('students.show', $ms->student->id) }}">{{ $ms->student->name }}</a></td>
                                        <td>{{ $ms->exam->name }}</td>
                                        <td>{{ $ms->total_marks }}</td>
                                        <td class="fw-bold text-primary">{{ $ms->obtained_marks }}</td>
                                        <td class="fw-bold">{{ $ms->percentage }}%</td>
                                        <td><span class="badge bg-light text-dark border">{{ $ms->grade }}</span></td>
                                        <td>
                                            @if($ms->result === 'PASS')
                                                <span class="badge bg-success text-white px-2 py-1">PASS</span>
                                            @else
                                                <span class="badge bg-danger text-white px-2 py-1">FAIL</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-muted py-4">No student scorecards match the query.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

            @else
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-chart-line fs-1 mb-3 text-secondary"></i>
                    <h5>No Report Generated</h5>
                    <p class="small">Configure report options on the left and click 'Generate'.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reportTypeSelect = document.getElementById('report_type');
        const subTypeSelect = document.getElementById('sub_type');
        
        // Form Filter Blocks
        const filterClass = document.getElementById('filter-class');
        const filterStudent = document.getElementById('filter-student');
        const filterExam = document.getElementById('filter-exam');
        const filterDate = document.getElementById('filter-date');
        const filterMonthYear = document.getElementById('filter-month-year');
        const filterRange = document.getElementById('filter-range');

        // Sub Type Mappings
        const subTypes = {
            student: [
                { value: 'all', label: 'All Registered Students' },
                { value: 'active', label: 'Active Enrolled Students' },
                { value: 'inactive', label: 'Inactive/Left Students' },
                { value: 'new_admissions', label: 'New Admissions Log' }
            ],
            fee: [
                { value: 'daily', label: 'Daily Fee Collections' },
                { value: 'monthly', label: 'Monthly Fee Collections' },
                { value: 'yearly', label: 'Yearly Fee Collections' },
                { value: 'arrears', label: 'Outstanding Arrears Roster' },
                { value: 'class_wise', label: 'Class-wise Collections Summary' }
            ],
            academic: [
                { value: 'all', label: 'All Exam Scores' },
                { value: 'passed', label: 'Passed Students List' },
                { value: 'failed', label: 'Failed Students List' }
            ]
        };

        // Current old selections if generating
        const currentReportType = "{{ $reportType ?? '' }}";
        const currentSubType = "{{ $subType ?? '' }}";

        reportTypeSelect.addEventListener('change', function () {
            const val = reportTypeSelect.value;
            if (!val) {
                subTypeSelect.innerHTML = '<option value="">Choose category first...</option>';
                subTypeSelect.setAttribute('disabled', 'disabled');
                hideAllFilters();
                return;
            }

            // Populate Sub-Types dropdown
            subTypeSelect.innerHTML = '<option value="">Select Sub-Type...</option>';
            subTypes[val].forEach(item => {
                subTypeSelect.innerHTML += `<option value="${item.value}" ${currentSubType === item.value ? 'selected' : ''}>${item.label}</option>`;
            });
            subTypeSelect.removeAttribute('disabled');
            showFilters();
        });

        subTypeSelect.addEventListener('change', showFilters);

        function showFilters() {
            hideAllFilters();
            
            const cat = reportTypeSelect.value;
            const sub = subTypeSelect.value;

            if (!cat || !sub) return;

            // General class filter fits almost everywhere
            filterClass.classList.remove('d-none');

            if (cat === 'student') {
                if (sub === 'new_admissions') {
                    filterRange.classList.remove('d-none'); // Date range for new admissions
                    filterClass.classList.add('d-none'); // Disable class filter here
                }
            } else if (cat === 'fee') {
                filterClass.classList.add('d-none'); // Class-wise summary has its own group

                if (sub === 'daily') {
                    filterDate.classList.remove('d-none');
                } else if (sub === 'monthly') {
                    filterMonthYear.classList.remove('d-none');
                } else if (sub === 'yearly') {
                    filterMonthYear.classList.remove('d-none');
                    // Hide month dropdown dynamically
                    document.getElementById('month').parentElement.classList.add('d-none');
                } else if (sub === 'arrears') {
                    filterClass.classList.remove('d-none'); // Show class filter for arrears list
                }
            } else if (cat === 'academic') {
                filterExam.classList.remove('d-none');
            }
        }

        function hideAllFilters() {
            document.querySelectorAll('.filter-item').forEach(item => {
                item.classList.add('d-none');
            });
            // Ensure month is visible by default
            document.getElementById('month').parentElement.classList.remove('d-none');
        }

        // Initialize if editing/viewing generated output
        if (currentReportType) {
            reportTypeSelect.value = currentReportType;
            reportTypeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
