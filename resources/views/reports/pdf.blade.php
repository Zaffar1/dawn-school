<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 11px;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }
        .school-details {
            font-size: 9px;
            color: #666;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: center;
        }
        .report-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .footer-table {
            width: 100%;
            margin-top: 60px;
        }
        .signature-line {
            width: 150px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
        }
        .signature-box {
            text-align: center;
            font-size: 9px;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- Header area -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 70%;">
                <h2 class="school-name">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h2>
                <p class="school-details">
                    Address: {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}
                </p>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <div style="font-size: 8px; color: #666;">
                    Academic Session: {{ $school->academic_session ?? '2026-2027' }}<br>
                    Generated on: {{ date('d-M-Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">{{ $title }}</div>

    <!-- 1. STUDENT DIRECTORY REPORT -->
    @if($reportType === 'student')
        <table class="report-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 80px;">Admission No</th>
                    <th class="text-left">Student Name</th>
                    <th>Father Name</th>
                    <th>Class</th>
                    <th>Roll No</th>
                    <th>Admission Date</th>
                    <th>Arrears Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $st)
                    <tr>
                        <td style="font-family: monospace;">{{ $st->admission_number }}</td>
                        <td class="text-left bold">{{ $st->name }}</td>
                        <td>{{ $st->father_name }}</td>
                        <td>{{ $st->class->name }}</td>
                        <td>{{ $st->roll_number }}</td>
                        <td>{{ $st->admission_date->format('d-M-Y') }}</td>
                        <td class="text-right bold">Rs. {{ number_format($st->arrears, 2) }}</td>
                        <td>{{ strtoupper($st->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8">No student records found.</td></tr>
                @endforelse
            </tbody>
        </table>

    <!-- 2. FINANCIAL REPORT -->
    @elseif($reportType === 'fee')
        @if($subType === 'arrears')
            <table class="report-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 85px;">Admission No</th>
                        <th class="text-left">Student Name</th>
                        <th>Father Name</th>
                        <th>Class</th>
                        <th class="text-right">Outstanding Arrears Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tArr = 0; @endphp
                    @forelse($data as $st)
                        @php $tArr += $st->arrears; @endphp
                        <tr>
                            <td style="font-family: monospace;">{{ $st->admission_number }}</td>
                            <td class="text-left bold">{{ $st->name }}</td>
                            <td>{{ $st->father_name }}</td>
                            <td>{{ $st->class->name }}</td>
                            <td class="text-right bold" style="color: #b45309;">Rs. {{ number_format($st->arrears, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No outstanding arrears.</td></tr>
                    @endforelse
                    @if(count($data) > 0)
                        <tr style="font-weight: bold; background-color: #f1f5f9;">
                            <td colspan="4" class="text-left">GRAND TOTAL OUTSTANDING ARREARS:</td>
                            <td class="text-right" style="color: #b45309;">Rs. {{ number_format($tArr, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @elseif($subType === 'class_wise')
            <table class="report-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-left">Class Name</th>
                        <th class="text-right">Admission Fees Collected</th>
                        <th class="text-right">Monthly Tuition Collected</th>
                        <th class="text-right">Exam Fees Collected</th>
                        <th class="text-right">Total Class Collections</th>
                    </tr>
                </thead>
                <tbody>
                    @php $gT = 0; @endphp
                    @forelse($data as $c)
                        @php $gT += $c->total_collected; @endphp
                        <tr>
                            <td class="text-left bold">{{ $c->class_name }}</td>
                            <td class="text-right">Rs. {{ number_format($c->total_admission, 2) }}</td>
                            <td class="text-right">Rs. {{ number_format($c->total_monthly, 2) }}</td>
                            <td class="text-right">Rs. {{ number_format($c->total_exam, 2) }}</td>
                            <td class="text-right bold" style="color: #10b981;">Rs. {{ number_format($c->total_collected, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No collection summary log found.</td></tr>
                    @endforelse
                    @if(count($data) > 0)
                        <tr style="font-weight: bold; background-color: #f1f5f9;">
                            <td class="text-left">GRAND TOTAL FEES COLLECTED:</td>
                            <td colspan="4" class="text-right" style="color: #10b981; font-size: 13px;">Rs. {{ number_format($gT, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @else
            <table class="report-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 90px;">Receipt No</th>
                        <th>Date</th>
                        <th class="text-left">Student Name</th>
                        <th>Class</th>
                        <th class="text-right">Paid Amount</th>
                        <th class="text-right">Outstanding Arrears Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tPaid = 0; @endphp
                    @forelse($data as $rec)
                        @php $tPaid += $rec->paid_amount; @endphp
                        <tr>
                            <td style="font-family: monospace;">{{ $rec->receipt_number }}</td>
                            <td>{{ $rec->date->format('d-M-Y') }}</td>
                            <td class="text-left bold">{{ $rec->student->name }}</td>
                            <td>{{ $rec->student->class->name }}</td>
                            <td class="text-right bold" style="color: #10b981;">Rs. {{ number_format($rec->paid_amount, 2) }}</td>
                            <td class="text-right">Rs. {{ number_format($rec->remaining_arrears, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No collection receipts registered for selected criteria.</td></tr>
                    @endforelse
                    @if(count($data) > 0)
                        <tr style="font-weight: bold; background-color: #f1f5f9;">
                            <td colspan="4" class="text-left">TOTAL FUNDS COLLECTED:</td>
                            <td class="text-right" style="color: #10b981;">Rs. {{ number_format($tPaid, 2) }}</td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

    <!-- 3. ACADEMIC SCORES REPORT -->
    @elseif($reportType === 'academic')
        <table class="report-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 60px;">Roll No</th>
                    <th class="text-left">Student Name</th>
                    <th>Exam Name</th>
                    <th>Session</th>
                    <th>Total Max</th>
                    <th>Obtained</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $ms)
                    <tr>
                        <td>{{ $ms->student->roll_number }}</td>
                        <td class="text-left bold">{{ $ms->student->name }}</td>
                        <td>{{ $ms->exam->name }}</td>
                        <td>{{ $ms->academic_session }}</td>
                        <td>{{ $ms->total_marks }}</td>
                        <td class="bold">{{ $ms->obtained_marks }}</td>
                        <td class="bold">{{ $ms->percentage }}%</td>
                        <td>{{ $ms->grade }}</td>
                        <td class="bold" style="{{ $ms->result==='PASS' ? 'color: #10b981;' : 'color: #ef4444;' }}">{{ $ms->result }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9">No academic marksheets match the query filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- Footer signatures -->
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 50%;" class="signature-box">
                <div class="signature-line"></div>
                Class Teacher Signature
            </td>
            <td style="width: 50%; text-align: right;" class="signature-box">
                <div class="signature-line" style="margin-left: auto;"></div>
                Principal Signature<br>
                ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
            </td>
        </tr>
    </table>

</body>
</html>
