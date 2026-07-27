<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .school-details {
            font-size: 11px;
            color: #666;
            margin: 0;
        }
        .receipt-badge {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 6px 12px;
            border-radius: 4px;
            text-align: left;
        }
        .doc-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px 6px;
            font-size: 12px;
        }
        .label {
            color: #666;
            font-weight: 500;
        }
        .value {
            font-weight: bold;
            color: #111;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .details-table th, .details-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: center;
            font-size: 12px;
        }
        .details-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
        }
        .total-section {
            width: 100%;
            margin-top: 15px;
        }
        .total-box {
            width: 250px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            padding: 10px;
            margin-left: auto;
        }
        .total-row {
            clear: both;
            overflow: hidden;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .total-label {
            float: left;
            color: #666;
        }
        .total-value {
            float: right;
            font-weight: bold;
        }
        .footer-table {
            width: 100%;
            margin-top: 70px;
        }
        .signature-line {
            width: 170px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
        }
        .signature-box {
            text-align: center;
            font-size: 11px;
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
                    Address: {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                    Phone: {{ $school->phone ?? '0300-1234567' }} | Email: {{ $school->email ?? 'info@superdawnschool.edu.pk' }}
                </p>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <div class="receipt-badge">
                    <span style="font-size: 9px; color: #666; display: block; font-family: monospace;">RECEIPT NO.</span>
                    <strong style="font-size: 13px; color: #1e3a8a; font-family: monospace;">{{ $receipt->receipt_number }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">Student Payment Fee Receipt</div>

    <!-- Student details grid -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label" style="width: 15%;">Student Name:</td>
            <td class="value" style="width: 35%;">{{ $receipt->student->name }}</td>
            <td class="label" style="width: 15%;">Receipt Date:</td>
            <td class="value" style="width: 35%;">{{ $receipt->date->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td class="label">Father Name:</td>
            <td class="value">{{ $receipt->student->father_name }}</td>
            <td class="label">Session:</td>
            <td class="value">{{ $school->academic_session ?? '2026-2027' }}</td>
        </tr>
        <tr>
            <td class="label">Class Name:</td>
            <td class="value" style="color: #1e3a8a;">{{ $receipt->student->class->name }}</td>
            <td class="label">Roll Number:</td>
            <td class="value">{{ $receipt->student->roll_number }}</td>
        </tr>
    </table>

    <!-- Dues structure table -->
    <table class="details-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: left;">Category</th>
                <th>Previous Arrears</th>
                <th>Admission Fee</th>
                <th>Monthly Tuition</th>
                <th>Exam Fee</th>
                <th style="text-align: right;">Total Amount Due</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; font-weight: bold;">Standard Dues</td>
                <td>Rs. {{ number_format($receipt->previous_arrears, 2) }}</td>
                <td>Rs. {{ number_format($receipt->admission_fee, 2) }}</td>
                <td>Rs. {{ number_format($receipt->monthly_fee, 2) }}</td>
                <td>Rs. {{ number_format($receipt->exam_fee, 2) }}</td>
                <td style="text-align: right; font-weight: bold;">Rs. {{ number_format($receipt->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Payment box -->
    <table class="total-section" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <span style="font-size: 11px; padding: 4px 8px; background-color: #d1fae5; color: #065f46; border-radius: 4px; font-weight: bold; font-family: sans-serif;">
                    Payment Status: PAID
                </span>
            </td>
            <td style="width: 50%;">
                <div class="total-box">
                    <div class="total-row">
                        <span class="total-label">Total Amount Due:</span>
                        <span class="total-value">Rs. {{ number_format($receipt->total_amount, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label" style="color: #10b981; font-weight: bold;">Amount Paid Now:</span>
                        <span class="total-value" style="color: #10b981;">Rs. {{ number_format($receipt->paid_amount, 2) }}</span>
                    </div>
                    <div style="border-top: 1px solid #cbd5e1; margin-top: 5px; padding-top: 5px;"></div>
                    <div class="total-row" style="font-weight: bold; margin-bottom: 0;">
                        <span class="total-label" style="color: #b45309;">Remaining Arrears:</span>
                        <span class="total-value" style="color: #b45309;">Rs. {{ number_format($receipt->remaining_arrears, 2) }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

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
