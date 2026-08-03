<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12px 18px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 9px;
            line-height: 1.3;
            background-color: #ffffff;
        }
        .receipt-container {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            background-color: #ffffff;
            margin-bottom: 2px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .school-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 1px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .school-details {
            font-size: 8px;
            color: #475569;
            margin: 0;
        }
        .receipt-badge {
            border: 1px solid #94a3b8;
            background-color: #f8fafc;
            padding: 2.5px 6px;
            border-radius: 4px;
            text-align: left;
            display: inline-block;
        }
        .copy-tag {
            font-size: 8px;
            font-weight: bold;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            margin-bottom: 2px;
        }
        .doc-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1px solid #cbd5e1;
        }
        .info-table td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
            vertical-align: middle;
        }
        .info-label {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            width: 15%;
        }
        .info-value {
            color: #0f172a;
            font-weight: bold;
            width: 35%;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .details-table th, .details-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            text-align: center;
            font-size: 8.5px;
        }
        .details-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
        }
        .total-section {
            width: 100%;
            margin-top: 2px;
        }
        .total-box {
            width: 210px;
            border: 2px solid #10b981;
            border-radius: 6px;
            background-color: #f0fdf4;
            padding: 6px;
            margin-left: auto;
        }
        .total-row {
            clear: both;
            overflow: hidden;
            margin-bottom: 2px;
            font-size: 8.5px;
        }
        .total-label {
            float: left;
            color: #475569;
        }
        .total-value {
            float: right;
            font-weight: bold;
            color: #0f172a;
        }
        .footer-table {
            width: 100%;
            margin-top: 10px;
        }
        .signature-line {
            width: 110px;
            border-bottom: 1px solid #475569;
            margin-bottom: 2px;
        }
        .signature-box {
            text-align: center;
            font-size: 8px;
            color: #475569;
        }
        .cut-line {
            border-top: 1px dashed #64748b;
            margin: 10px 0;
            text-align: center;
            height: 1px;
        }
        .cut-text {
            font-size: 7.5px;
            color: #64748b;
            background: #ffffff;
            padding: 0 10px;
            position: relative;
            top: -5px;
            font-family: monospace;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

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

    <!-- ==================== 1st COPY: SCHOOL COPY ==================== -->
    <div class="receipt-container">
        <!-- Header area -->
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 70%;">
                    <h2 class="school-name">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h2>
                    <p class="school-details">
                        Address: {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                        Phone: {{ $school->phone ?? '0300-1234567' }}
                    </p>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: middle;">
                    <div class="copy-tag" style="background-color: #ef4444;">School Copy</div>
                    <br>
                    <div class="receipt-badge">
                        <span style="font-size: 7px; color: #64748b; display: block; font-family: monospace;">RECEIPT NO.</span>
                        <strong style="font-size: 10px; color: #1e3a8a; font-family: monospace;">{{ $receipt->receipt_number }}</strong>
                    </div>
                </td>
            </tr>
        </table>

        <div class="doc-title">Student Payment Fee Receipt</div>

        <!-- Student details structured grid table -->
        <table class="info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="info-label">Student Name</td>
                <td class="info-value">{{ $receipt->student->name }}</td>
                <td class="info-label">Receipt Date</td>
                <td class="info-value">{{ $receipt->date->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Father Name</td>
                <td class="info-value">{{ $receipt->student->father_name }}</td>
                <td class="info-label">Session</td>
                <td class="info-value">{{ $school->academic_session ?? '2026-2027' }}</td>
            </tr>
            <tr>
                <td class="info-label">Class & Sec</td>
                <td class="info-value" style="color: #1e3a8a;">{{ $receipt->student->class->name }} - {{ $receipt->student->section ?? 'A' }}</td>
                <td class="info-label">Roll Number</td>
                <td class="info-value">{{ $receipt->student->roll_number }}</td>
            </tr>
        </table>

        <!-- Fee Breakdown Table (Current Fees) -->
        <table class="details-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: left; width: 60%;">Current Fee Category</th>
                    <th style="text-align: right; width: 40%;">Amount Collected (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @if($receipt->admission_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Admission Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->monthly_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Monthly Tuition Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->exam_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Exam Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->admission_fee == 0 && $receipt->monthly_fee == 0 && $receipt->exam_fee == 0)
                <tr>
                    <td style="text-align: left; padding: 4px; color: #888;">No Current Month Fees collected.</td>
                    <td style="text-align: right; padding: 4px;">Rs. 0.00</td>
                </tr>
                @endif
                <tr style="font-weight: bold; background-color: #f8fafc; border-top: 1px solid #cbd5e1;">
                    <td style="text-align: left; padding: 5px;">Total Current Fee</td>
                    <td style="text-align: right; padding: 5px;">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Arrears Payments Table -->
        @if(count($arrearsDetails) > 0)
        <table class="details-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">Arrears Month Paid</th>
                    <th style="text-align: center; width: 25%;">Status</th>
                    <th style="text-align: right; width: 30%;">Amount Paid (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arrearsDetails as $item)
                <tr>
                    <td style="text-align: left; padding: 4px; font-weight: bold;">{{ $item['label'] }}</td>
                    <td style="text-align: center; padding: 4px;">
                        <span style="font-size: 7.5px; padding: 1px 3px; border: 1px solid #86efac; background-color: #f0fdf4; color: #166534; border-radius: 2px;">
                            {{ $item['status'] === 'paid' ? 'Paid' : 'Partial' }}
                        </span>
                    </td>
                    <td style="text-align: right; padding: 4px; font-weight: bold; color: #1e3a8a;">Rs. {{ number_format($item['allocated'], 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f8fafc; border-top: 1px solid #cbd5e1;">
                    <td colspan="2" style="text-align: left; padding: 5px;">Total Arrears Collected</td>
                    <td style="text-align: right; padding: 5px; color: #1e3a8a;">Rs. {{ number_format($totalArrearsPaid, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Payment box & Summary -->
        <table class="total-section" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: left;">
                    <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px; background-color: #f8fafc; width: 90%;">
                        <div style="font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; padding-bottom: 2px; margin-bottom: 4px; font-size: 8px;">Remaining Pending Arrears:</div>
                        @forelse($studentPendingArrears as $pending)
                            @php
                                $pendingLabel = date('F Y', strtotime($pending->month . '-01'));
                            @endphp
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1px; border: none;">
                                <tr>
                                    <td style="text-align: left; border: none; padding: 0; font-size: 8px; color: #475569;">
                                        {{ $pendingLabel }} ({{ $pending->payment_status === 'partially_paid' ? 'Partial' : 'Unpaid' }}):
                                    </td>
                                    <td style="text-align: right; border: none; padding: 0; font-size: 8px; font-weight: bold; color: #b45309;">
                                        Rs. {{ number_format($pending->amount, 2) }}
                                    </td>
                                </tr>
                            </table>
                        @empty
                            <div style="color: #166534; font-weight: bold; font-size: 8px;">No outstanding arrears left!</div>
                        @endforelse
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div class="total-box">
                        <div class="total-row">
                            <span class="total-label">Total Current Fee:</span>
                            <span class="total-value">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Total Arrears Collected:</span>
                            <span class="total-value">Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                        </div>
                        <hr style="border: 0; border-top: 1px solid #10b981; margin: 2px 0;">
                        <div class="total-row" style="font-weight: bold; font-size: 10px; margin-bottom: 0;">
                            <span class="total-label" style="color: #059669;">Grand Total Received:</span>
                            <span class="total-value" style="color: #059669;">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Footer signatures -->
        <table class="footer-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 55%;" class="signature-box">
                    <div class="signature-line"></div>
                    Class Teacher Signature
                </td>
                <td style="width: 45%; text-align: right;" class="signature-box">
                    <div class="signature-line" style="margin-left: auto;"></div>
                    Principal Signature ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                </td>
            </tr>
        </table>
    </div>

    <!-- ==================== CUT HERE LINE ==================== -->
    <div class="cut-line">
        <span class="cut-text">✂------------------ CUT HERE ------------------✂</span>
    </div>

    <!-- ==================== 2nd COPY: PARENT COPY ==================== -->
    <div class="receipt-container">
        <!-- Header area -->
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 70%;">
                    <h2 class="school-name">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h2>
                    <p class="school-details">
                        Address: {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                        Phone: {{ $school->phone ?? '0300-1234567' }}
                    </p>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: middle;">
                    <div class="copy-tag" style="background-color: #3b82f6;">Parent Copy</div>
                    <br>
                    <div class="receipt-badge">
                        <span style="font-size: 7px; color: #64748b; display: block; font-family: monospace;">RECEIPT NO.</span>
                        <strong style="font-size: 10px; color: #1e3a8a; font-family: monospace;">{{ $receipt->receipt_number }}</strong>
                    </div>
                </td>
            </tr>
        </table>

        <div class="doc-title">Student Payment Fee Receipt</div>

        <!-- Student details structured grid table -->
        <table class="info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="info-label">Student Name</td>
                <td class="info-value">{{ $receipt->student->name }}</td>
                <td class="info-label">Receipt Date</td>
                <td class="info-value">{{ $receipt->date->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Father Name</td>
                <td class="info-value">{{ $receipt->student->father_name }}</td>
                <td class="info-label">Session</td>
                <td class="info-value">{{ $school->academic_session ?? '2026-2027' }}</td>
            </tr>
            <tr>
                <td class="info-label">Class & Sec</td>
                <td class="info-value" style="color: #1e3a8a;">{{ $receipt->student->class->name }} - {{ $receipt->student->section ?? 'A' }}</td>
                <td class="info-label">Roll Number</td>
                <td class="info-value">{{ $receipt->student->roll_number }}</td>
            </tr>
        </table>

        <!-- Fee Breakdown Table (Current Fees) -->
        <table class="details-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: left; width: 60%;">Current Fee Category</th>
                    <th style="text-align: right; width: 40%;">Amount Collected (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @if($receipt->admission_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Admission Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->monthly_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Monthly Tuition Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->exam_fee > 0)
                <tr>
                    <td style="text-align: left; padding: 4px;">Exam Fee</td>
                    <td style="text-align: right; padding: 4px;">Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                </tr>
                @endif
                @if($receipt->admission_fee == 0 && $receipt->monthly_fee == 0 && $receipt->exam_fee == 0)
                <tr>
                    <td style="text-align: left; padding: 4px; color: #888;">No Current Month Fees collected.</td>
                    <td style="text-align: right; padding: 4px;">Rs. 0.00</td>
                </tr>
                @endif
                <tr style="font-weight: bold; background-color: #f8fafc; border-top: 1px solid #cbd5e1;">
                    <td style="text-align: left; padding: 5px;">Total Current Fee</td>
                    <td style="text-align: right; padding: 5px;">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Arrears Payments Table -->
        @if(count($arrearsDetails) > 0)
        <table class="details-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">Arrears Month Paid</th>
                    <th style="text-align: center; width: 25%;">Status</th>
                    <th style="text-align: right; width: 30%;">Amount Paid (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arrearsDetails as $item)
                <tr>
                    <td style="text-align: left; padding: 4px; font-weight: bold;">{{ $item['label'] }}</td>
                    <td style="text-align: center; padding: 4px;">
                        <span style="font-size: 7.5px; padding: 1px 3px; border: 1px solid #86efac; background-color: #f0fdf4; color: #166534; border-radius: 2px;">
                            {{ $item['status'] === 'paid' ? 'Paid' : 'Partial' }}
                        </span>
                    </td>
                    <td style="text-align: right; padding: 4px; font-weight: bold; color: #1e3a8a;">Rs. {{ number_format($item['allocated'], 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f8fafc; border-top: 1px solid #cbd5e1;">
                    <td colspan="2" style="text-align: left; padding: 5px;">Total Arrears Collected</td>
                    <td style="text-align: right; padding: 5px; color: #1e3a8a;">Rs. {{ number_format($totalArrearsPaid, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Payment box & Summary -->
        <table class="total-section" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: left;">
                    <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px; background-color: #f8fafc; width: 90%;">
                        <div style="font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; padding-bottom: 2px; margin-bottom: 4px; font-size: 8px;">Remaining Pending Arrears:</div>
                        @forelse($studentPendingArrears as $pending)
                            @php
                                $pendingLabel = date('F Y', strtotime($pending->month . '-01'));
                            @endphp
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1px; border: none;">
                                <tr>
                                    <td style="text-align: left; border: none; padding: 0; font-size: 8px; color: #475569;">
                                        {{ $pendingLabel }} ({{ $pending->payment_status === 'partially_paid' ? 'Partial' : 'Unpaid' }}):
                                    </td>
                                    <td style="text-align: right; border: none; padding: 0; font-size: 8px; font-weight: bold; color: #b45309;">
                                        Rs. {{ number_format($pending->amount, 2) }}
                                    </td>
                                </tr>
                            </table>
                        @empty
                            <div style="color: #166534; font-weight: bold; font-size: 8px;">No outstanding arrears left!</div>
                        @endforelse
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div class="total-box">
                        <div class="total-row">
                            <span class="total-label">Total Current Fee:</span>
                            <span class="total-value">Rs. {{ number_format($receipt->admission_fee + $receipt->monthly_fee + $receipt->exam_fee, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Total Arrears Collected:</span>
                            <span class="total-value">Rs. {{ number_format($totalArrearsPaid, 2) }}</span>
                        </div>
                        <hr style="border: 0; border-top: 1px solid #10b981; margin: 2px 0;">
                        <div class="total-row" style="font-weight: bold; font-size: 10px; margin-bottom: 0;">
                            <span class="total-label" style="color: #059669;">Grand Total Received:</span>
                            <span class="total-value" style="color: #059669;">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Footer signatures -->
        <table class="footer-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 55%;" class="signature-box">
                    <div class="signature-line"></div>
                    Class Teacher Signature
                </td>
                <td style="width: 45%; text-align: right;" class="signature-box">
                    <div class="signature-line" style="margin-left: auto;"></div>
                    Principal Signature ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
