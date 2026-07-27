<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Result - {{ $class->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }
        .school-details {
            font-size: 10px;
            color: #666;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-label {
            color: #666;
            font-weight: bold;
        }
        .meta-val {
            font-weight: bold;
            color: #1e3a8a;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .result-table th, .result-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: center;
        }
        .result-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-left {
            text-align: left;
        }
        .footer-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-line {
            width: 150px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
        }
        .signature-box {
            text-align: center;
            font-size: 10px;
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
                <div style="font-size: 9px; color: #666;">
                    Generated on: {{ date('d-M-Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">Class Result Summary Sheet</div>

    <!-- Metadata Grid -->
    <table class="meta-table">
        <tr>
            <td class="meta-label" style="width: 10%;">Class Name:</td>
            <td class="meta-val" style="width: 23%;">{{ $class->name }}</td>
            <td class="meta-label" style="width: 10%;">Examination:</td>
            <td class="meta-val" style="width: 23%;">{{ $exam->name }}</td>
            <td class="meta-label" style="width: 12%;">Academic Session:</td>
            <td class="meta-val" style="width: 22%;">{{ $session ?? $school->academic_session }}</td>
        </tr>
    </table>

    <!-- Roster Sheet Table -->
    <table class="result-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 60px;">Roll No</th>
                <th class="text-left">Student Name</th>
                <th style="width: 100px;">Admission No</th>
                <th style="width: 80px;">Total Marks</th>
                <th style="width: 90px;">Obtained Marks</th>
                <th style="width: 80px;">Percentage</th>
                <th style="width: 60px;">Grade</th>
                <th style="width: 70px;">Result</th>
            </tr>
        </thead>
        <tbody>
            @forelse($marksheets as $ms)
                <tr>
                    <td>{{ $ms->student->roll_number }}</td>
                    <td class="text-left" style="font-weight: bold;">{{ $ms->student->name }}</td>
                    <td style="font-family: monospace;">{{ $ms->student->admission_number }}</td>
                    <td>{{ $ms->total_marks }}</td>
                    <td style="font-weight: bold; color: #1e3a8a;">{{ $ms->obtained_marks }}</td>
                    <td style="font-weight: bold;">{{ $ms->percentage }}%</td>
                    <td>{{ $ms->grade }}</td>
                    <td style="font-weight: bold; {{ $ms->result === 'PASS' ? 'color: #10b981;' : 'color: #ef4444;' }}">
                        {{ $ms->result }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No student marksheets generated for this exam yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signatures -->
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
