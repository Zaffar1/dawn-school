<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marksheet - {{ $marksheet->student->name }}</title>
    <style>
        @page {
            margin: 10px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            height: 100%;
        }
        
        /* Double blue border certificate frame */
        .certificate-border {
            border: 8px double #1e3a8a;
            padding: 10px;
            position: absolute;
            top: 5px;
            bottom: 5px;
            left: 5px;
            right: 5px;
            box-sizing: border-box;
        }
        
        .certificate-inner {
            border: 1px dashed #1e3a8a;
            padding: 15px;
            height: 98.2%;
            box-sizing: border-box;
        }

        /* Header tables */
        .header-table {
            width: 100%;
            border-bottom: 2px double #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        
        .school-title {
            font-size: 19px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
            text-align: center;
        }
        
        .school-subtitle {
            font-size: 9px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 2px 0 0 0;
            text-align: center;
        }
        
        .school-address {
            font-size: 7.5px;
            color: #475569;
            margin: 2px 0 0 0;
            text-align: center;
            font-style: italic;
        }
        
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 5px 0 2px 0;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        
        .doc-subtitle {
            text-align: center;
            font-size: 8.5px;
            font-style: italic;
            color: #475569;
            margin: 0;
        }
        
        /* Formula boxes style */
        .formula-box {
            border: 1px solid #1e3a8a;
            background-color: #f8fafc;
            width: 100%;
        }
        
        .formula-title {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
            padding: 2px;
            text-transform: uppercase;
        }
        
        .formula-table {
            width: 100%;
            font-size: 6.5px;
            padding: 3px;
            line-height: 1.2;
        }
        
        /* Profile Info table */
        .profile-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 10px;
        }
        
        .profile-table td {
            padding: 3px 2px;
            vertical-align: middle;
        }
        
        .profile-label {
            font-weight: bold;
            color: #1e3a8a;
            white-space: nowrap;
        }
        
        .profile-value-underline {
            border-bottom: 1px dotted #1e3a8a;
            font-weight: bold;
            padding-left: 5px;
            color: #0f172a;
        }

        /* Marks Table */
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1.5px solid #1e3a8a;
        }
        
        .marks-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            border: 1px solid #1e3a8a;
            padding: 4px 4px;
        }
        
        .marks-table td {
            border: 1px solid #1e3a8a;
            padding: 4px;
            text-align: center;
            font-size: 9px;
        }
        
        .marks-table .subject-col {
            text-align: left;
            font-weight: bold;
            background-color: #f8fafc;
            color: #1e3a8a;
        }

        /* Summary bottom block */
        .summary-block-table {
            width: 100%;
            margin-top: 4px;
        }

        .summary-line {
            font-size: 10px;
            margin-bottom: 4px;
            padding: 1px 0;
        }

        .summary-underline {
            border-bottom: 1px solid #1e3a8a;
            font-weight: bold;
            display: inline-block;
            padding: 0 5px;
            color: #0f172a;
        }
        
        /* Note and footer */
        .note-text {
            font-size: 7.5px;
            font-style: italic;
            color: #475569;
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
        
        .signatures-table {
            width: 100%;
            margin-top: 20px;
        }
        
        .sig-line {
            border-top: 1px solid #1e3a8a;
            width: 150px;
            margin-bottom: 4px;
        }
        
        .sig-text {
            font-size: 8.5px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="certificate-border">
    <div class="certificate-inner">
        
        <!-- HEADER LAYOUT TABLE -->
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <!-- Left: Logo Image -->
                <td style="width: 16%; text-align: left; vertical-align: middle;">
                    <img src="{{ public_path('images/logo.jpg') }}" alt="School Logo" style="height: 75px; width: auto; border-radius: 4px;">
                </td>
                
                <!-- Center: School name / info -->
                <td style="width: 62%; text-align: center; vertical-align: middle;">
                    <h2 class="school-title">DAWN PUBLIC SCHOOL</h2>
                    <h4 class="school-subtitle">SUPER DAWN SCHOOL SYSTEM LAKHI</h4>
                    <p class="school-address">
                        {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur, Sindh' }}
                    </p>
                    <div class="doc-title">MARKS CERTIFICATE</div>
                    <p class="doc-subtitle">Certified showing the number of marks secured by</p>
                </td>
                
                <!-- Right: Grading Formula -->
                <td style="width: 22%; text-align: right; vertical-align: middle;">
                    <div class="formula-box">
                        <div class="formula-title">GRADING FORMULA</div>
                        <table class="formula-table" cellpadding="0" cellspacing="0">
                            <tr><td><strong>A-1</strong></td><td style="text-align: right;">80% & above</td></tr>
                            <tr><td><strong>A</strong></td><td style="text-align: right;">70% to 79%</td></tr>
                            <tr><td><strong>B</strong></td><td style="text-align: right;">60% to 69%</td></tr>
                            <tr><td><strong>C</strong></td><td style="text-align: right;">50% to 59%</td></tr>
                            <tr><td><strong>D</strong></td><td style="text-align: right;">40% to 49%</td></tr>
                            <tr><td><strong>E</strong></td><td style="text-align: right;">33% to 39%</td></tr>
                            <tr><td><strong>F</strong></td><td style="text-align: right;">Below 33%</td></tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- STUDENT PROFILE TABLE -->
        @php
            $className = $marksheet->student->class->name;
            $classParts = preg_split('/[\s\-\/]/', $className, 2);
            $classDisplay = $classParts[0] ?? $className;
            $sectionDisplay = $classParts[1] ?? 'B';
        @endphp
        
        <table class="profile-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 10%;" class="profile-label">G.R. No:</td>
                <td style="width: 40%;" class="profile-value-underline">{{ $marksheet->student->admission_number }}</td>
                
                <td style="width: 10%;" class="profile-label">Seat No:</td>
                <td style="width: 40%;" class="profile-value-underline">{{ $marksheet->student->roll_number }}</td>
            </tr>
            <tr>
                <td class="profile-label">Mr. / Miss:</td>
                <td class="profile-value-underline" style="text-transform: uppercase;">{{ $marksheet->student->name }}</td>
                
                <td class="profile-label">S/o, D/o:</td>
                <td class="profile-value-underline" style="text-transform: uppercase;">{{ $marksheet->student->father_name }}</td>
            </tr>
            <tr>
                <td class="profile-label">Class:</td>
                <td class="profile-value-underline">{{ $classDisplay }}</td>
                
                <td class="profile-label">Section:</td>
                <td class="profile-value-underline">{{ $sectionDisplay }}</td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 8px;">
                    <span class="profile-label">In each head of passing at the quarterly / terminal. Examination held in the month of:</span>
                    <span class="profile-value-underline" style="display: inline-block; width: 230px; text-align: center;">{{ $marksheet->exam->name }}</span>
                </td>
            </tr>
        </table>

        <!-- MARKS TABLE -->
        <table class="marks-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 35%; text-align: left;">Subject</th>
                    <th style="width: 15%;">Maximum Marks</th>
                    <th style="width: 15%;">Minimum Marks</th>
                    <th style="width: 15%;">Marks Obtained</th>
                    <th style="width: 12%;">Remarks</th>
                    <th style="width: 8%;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marksheet->marksheetSubjects as $subMark)
                    @php
                        $isPassed = $subMark->obtained_marks >= $subMark->passing_marks;
                        $subGrade = '';
                        if ($subMark->total_marks > 0) {
                            $subPct = ($subMark->obtained_marks / $subMark->total_marks) * 100;
                            if ($subPct >= 80) $subGrade = 'A-1';
                            elseif ($subPct >= 70) $subGrade = 'A';
                            elseif ($subPct >= 60) $subGrade = 'B';
                            elseif ($subPct >= 50) $subGrade = 'C';
                            elseif ($subPct >= 40) $subGrade = 'D';
                            elseif ($subPct >= 33) $subGrade = 'E';
                            else $subGrade = 'F';
                        }
                    @endphp
                    <tr>
                        <td class="subject-col">{{ $subMark->subject->name }}</td>
                        <td style="font-weight: bold;">{{ $subMark->total_marks }}</td>
                        <td>{{ $subMark->passing_marks }}</td>
                        <td style="font-weight: bold; {{ !$isPassed ? 'color: #ef4444;' : '' }}">{{ $subMark->obtained_marks }}</td>
                        <td style="font-weight: bold; {{ $isPassed ? 'color: #16a34a;' : 'color: #dc2626;' }}">
                            {{ $isPassed ? 'Pass' : 'Fail' }}
                        </td>
                        <td style="font-weight: bold;">{{ $subGrade }}</td>
                    </tr>
                @endforeach
                
                <!-- Total Row -->
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td class="subject-col" style="color: #1e3a8a;">TOTAL</td>
                    <td style="color: #1e3a8a;">{{ $marksheet->total_marks }}</td>
                    <td>-</td>
                    <td style="color: #1e3a8a; font-size: 11px;">{{ $marksheet->obtained_marks }}</td>
                    <td style="{{ $marksheet->result === 'PASS' ? 'color: #16a34a;' : 'color: #dc2626;' }}">{{ $marksheet->result }}</td>
                    <td style="color: #b45309;">{{ $marksheet->grade }}</td>
                </tr>
            </tbody>
        </table>

        <!-- SUMMARY & DIVISION FORMULA TABLE -->
        <table class="summary-block-table" cellpadding="0" cellspacing="0">
            <tr>
                <!-- Left: Text fields -->
                <td style="width: 76%; vertical-align: top;">
                    <div class="summary-line">
                        Total Marks in words: 
                        <span class="summary-underline" style="width: 320px;">{{ $marksheet->obtained_marks_in_words }} Only</span>
                    </div>
                    
                    <div class="summary-line">
                        Percentage: 
                        <span class="summary-underline" style="width: 100px; text-align: center;">{{ $marksheet->percentage }}%</span>
                        
                        <span style="margin-left: 20px;">Position:</span>
                        <span class="summary-underline" style="width: 100px; text-align: center;">{{ $marksheet->position }}</span>
                    </div>
                    
                    <div class="summary-line">
                        Attended Days: 
                        <span class="summary-underline" style="width: 80px; text-align: center;">______</span>
                        <span style="margin-left: 10px;">Out of:</span>
                        <span class="summary-underline" style="width: 80px; text-align: center;">______</span>
                    </div>
                </td>
                
                <!-- Right: Division Formula Box -->
                <td style="width: 24%; vertical-align: top; text-align: right;">
                    <div class="formula-box">
                        <div class="formula-title">DIVISION FORMULA</div>
                        <table class="formula-table" cellpadding="0" cellspacing="0" style="text-align: left;">
                            <tr><td><strong>First Class:</strong></td><td style="text-align: right;">60% & above</td></tr>
                            <tr><td><strong>Second Class:</strong></td><td style="text-align: right;">45% to 59%</td></tr>
                            <tr><td><strong>Third Class:</strong></td><td style="text-align: right;">33% to 44%</td></tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- NOTE -->
        <div class="note-text">
            <strong>Note:</strong> The School reserves the right of issuing any correction in the results if any mistake is detected later on.
        </div>

        <!-- FOOTER SIGNATURES TABLE -->
        <table class="signatures-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <div class="sig-line"></div>
                    <div class="sig-text">Class Teacher Sig</div>
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top;">
                    <div class="sig-line" style="margin-left: auto;"></div>
                    <div class="sig-text">PRINCIPAL</div>
                    <div style="font-size: 7.5px; color: #475569; margin-top: 2px;">
                        ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                    </div>
                </td>
            </tr>
        </table>

    </div>
</div>

</body>
</html>
