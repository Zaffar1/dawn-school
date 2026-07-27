<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Form - {{ $admission->student->name }}</title>
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
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .school-name {
            font-size: 22px;
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
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .label {
            color: #666;
            width: 30%;
            font-weight: 500;
        }
        .value {
            font-weight: bold;
            color: #111;
            width: 70%;
        }
        .fees-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .fees-table th, .fees-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
        }
        .fees-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer-table {
            width: 100%;
            margin-top: 70px;
        }
        .signature-line {
            width: 200px;
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

    <!-- 1. Header Area -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 75%;">
                <h2 class="school-name">{{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }}</h2>
                <p class="school-details">
                    Address: {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur' }}<br>
                    Phone: {{ $school->phone ?? '0300-1234567' }} | Email: {{ $school->email ?? 'info@superdawnschool.edu.pk' }}
                </p>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <div style="font-size: 10px; border: 1px solid #ddd; padding: 6px; border-radius: 4px; background: #fafafa; display: inline-block;">
                    <strong>Academic Session</strong><br>
                    <span style="font-size: 12px; color: #1e3a8a;">{{ $school->academic_session ?? '2026-2027' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Document title -->
    <div class="doc-title">Student Admission Application Form</div>

    <!-- 2. Personal Profile Section -->
    <div class="section-title">1. Personal Profile</div>
    <table class="info-table">
        <tr>
            <td class="label">Student Name:</td>
            <td class="value">{{ $admission->student->name }}</td>
        </tr>
        <tr>
            <td class="label">Father Name:</td>
            <td class="value">{{ $admission->student->father_name }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth:</td>
            <td class="value">{{ $admission->student->date_of_birth->format('d-F-Y') }}</td>
        </tr>
        <tr>
            <td class="label">Gender:</td>
            <td class="value">{{ ucfirst($admission->student->gender) }}</td>
        </tr>
        <tr>
            <td class="label">Contact Phone No:</td>
            <td class="value">{{ $admission->student->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Residential Address:</td>
            <td class="value">{{ $admission->student->address }}</td>
        </tr>
    </table>

    <!-- 3. Academic Details Section -->
    <div class="section-title">2. Enrollment Program</div>
    <table class="info-table">
        <tr>
            <td class="label">Admission Number:</td>
            <td class="value" style="font-family: monospace; font-size: 14px;">{{ $admission->student->admission_number }}</td>
        </tr>
        <tr>
            <td class="label">Target Class:</td>
            <td class="value">{{ $admission->class->name }}</td>
        </tr>
        <tr>
            <td class="label">Class Roll Number:</td>
            <td class="value">{{ $admission->student->roll_number }}</td>
        </tr>
        <tr>
            <td class="label">Admission Date:</td>
            <td class="value">{{ $admission->admission_date->format('d-M-Y') }}</td>
        </tr>
    </table>

    <!-- 4. Fee Configuration Section -->
    <div class="section-title">3. Fee Structure & Outstanding Ledger</div>
    <table class="fees-table">
        <thead>
            <tr>
                <th>Fee Head Category</th>
                <th class="text-right" style="width: 150px;">Amount (PKR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Admission Registration Fee</td>
                <td class="text-right">Rs. {{ number_format($admission->admission_fee, 2) }}</td>
            </tr>
            <tr>
                <td>Monthly Tuition Fee</td>
                <td class="text-right">Rs. {{ number_format($admission->monthly_fee, 2) }}</td>
            </tr>
            <tr>
                <td>Academic Examination Fee</td>
                <td class="text-right">Rs. {{ number_format($admission->exam_fee, 2) }}</td>
            </tr>
            <tr>
                <td>Outstanding Arrears / Prior Balance</td>
                <td class="text-right">Rs. {{ number_format($admission->arrears, 2) }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f8fafc;">
                <td>Grand Total Due:</td>
                <td class="text-right" style="color: #1e3a8a;">
                    Rs. {{ number_format($admission->admission_fee + $admission->monthly_fee + $admission->exam_fee + $admission->arrears, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- 5. Signatures Footer -->
    <table class="footer-table">
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
