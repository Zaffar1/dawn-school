@extends('layouts.app')

@section('title', 'Marks Certificate - ' . $marksheet->student->name)

@section('styles')
<style>
    /* Certificate Styles for Web View */
    .certificate-wrapper {
        background-color: #f0f4f8;
        padding: 30px 15px;
    }
    
    .certificate-card {
        background: #ffffff;
        border: 15px double #1e3a8a;
        padding: 35px;
        position: relative;
        color: #0f172a;
        font-family: 'Georgia', serif;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-radius: 4px;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .certificate-inner {
        border: 2px dashed #1e3a8a;
        padding: 25px;
    }
    
    /* Elegant blue border accents */
    .certificate-header {
        position: relative;
        border-bottom: 3px double #1e3a8a;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .school-title {
        color: #1e3a8a;
        font-family: 'Outfit', 'Helvetica Neue', Arial, sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
        letter-spacing: 0.5px;
        margin: 0;
        text-transform: uppercase;
        text-shadow: 1px 1px 1px rgba(0,0,0,0.1);
    }
    
    .cert-title {
        color: #1e3a8a;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 2px;
        margin-top: 15px;
        text-transform: uppercase;
    }
    
    .cert-subtitle {
        font-style: italic;
        color: #475569;
        font-size: 0.95rem;
        margin-top: 5px;
    }

    /* Formula boxes */
    .formula-box {
        border: 1px solid #1e3a8a;
        font-size: 0.72rem;
        font-family: Arial, sans-serif;
        color: #1e3a8a;
        background-color: #f8fafc;
        line-height: 1.3;
        border-radius: 4px;
    }
    
    .formula-title {
        background-color: #1e3a8a;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
        padding: 2px 5px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .formula-content {
        padding: 6px;
    }
    
    /* Student Details Info */
    .details-row {
        margin-bottom: 12px;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .details-label {
        font-weight: bold;
        color: #1e3a8a;
    }

    .details-value {
        border-bottom: 1px dotted #1e3a8a;
        font-weight: bold;
        padding-left: 5px;
        padding-right: 5px;
        display: inline-block;
        color: #0f172a;
    }
    
    /* Table styles */
    .marks-table {
        border: 2px solid #1e3a8a !important;
        font-family: Arial, sans-serif;
        font-size: 0.9rem;
    }
    
    .marks-table th {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        border: 1px solid #1e3a8a !important;
        padding: 10px 8px;
    }
    
    .marks-table td {
        border: 1px solid #1e3a8a !important;
        padding: 8px;
        text-align: center;
    }
    
    .marks-table .subject-col {
        text-align: left;
        font-weight: bold;
        background-color: #f8fafc;
        color: #1e3a8a;
    }

    .summary-text-line {
        font-size: 0.98rem;
        margin-bottom: 12px;
        line-height: 1.8;
    }
    
    .summary-text-line .value-underline {
        border-bottom: 1px solid #1e3a8a;
        font-weight: bold;
        padding: 0 10px;
        display: inline-block;
        color: #0f172a;
    }
    
    /* Signatures */
    .signature-area {
        margin-top: 50px;
        font-family: 'Outfit', sans-serif;
    }
    
    .sig-line {
        border-top: 1px solid #1e3a8a;
        width: 180px;
        margin: 40px auto 5px auto;
    }
    
    .sig-title {
        font-weight: bold;
        color: #1e3a8a;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    
    .school-note {
        font-size: 0.78rem;
        font-style: italic;
        color: #475569;
        margin-top: 25px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }

    /* Print styling */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0.8cm;
        }
        body {
            background: none;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page-title-box, .top-header, #sidebar, .dropdown, .btn {
            display: none !important;
        }
        #content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
        }
        .certificate-wrapper {
            padding: 0;
            background: none;
            height: auto;
        }
        .certificate-card {
            border: 12px double #1e3a8a !important;
            box-shadow: none !important;
            padding: 15px !important;
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        .certificate-inner {
            border: 2px dashed #1e3a8a !important;
            padding: 15px !important;
            box-sizing: border-box;
        }
        .signature-area {
            margin-top: 30px !important;
        }
        .sig-line {
            margin-top: 25px !important;
        }
        .school-note {
            margin-top: 12px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-title-box d-print-none">
    <div>
        <h3>Academic Marksheet Certificate</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marksheets.index') }}">Marksheets</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $marksheet->student->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <button onclick="window.print();" class="btn btn-primary me-2"><i class="fa-solid fa-print me-2"></i>Print Certificate</button>
        <a href="{{ route('marksheets.pdf', $marksheet->id) }}" class="btn btn-outline-danger me-2"><i class="fa-solid fa-file-pdf me-2"></i>Download PDF</a>
        <a href="{{ route('marksheets.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
</div>

<div class="certificate-wrapper">
    <div class="certificate-card">
        <div class="certificate-inner">
            
            <!-- HEADER SECTION -->
            <div class="certificate-header">
                <div class="row align-items-center">
                    <!-- Left: Logo Image -->
                    <div class="col-2 text-start">
                        <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" style="height: 100px; width: auto; max-width: 100%; display: block; border-radius: 4px;">
                    </div>
                    
                    <!-- Center: School Name and Document Title -->
                    <div class="col-7 text-center">
                        <h2 class="school-title">DAWN PUBLIC SCHOOL</h2>
                        <div style="font-size: 0.92rem; font-weight: bold; color: #1e3a8a; margin: 4px 0;">SUPER DAWN SCHOOL SYSTEM LAKHI</div>
                        <div style="font-size: 0.72rem; color: #475569; font-style: italic;">
                            {{ $school->address ?? 'Main Bazar Lakhi, Tehsil & District Shikarpur, Sindh' }}
                        </div>
                        <h3 class="cert-title">MARKS CERTIFICATE</h3>
                        <p class="cert-subtitle">Certified showing the number of marks secured by</p>
                    </div>
                    
                    <!-- Right: Grading Formula -->
                    <div class="col-3">
                        <div class="formula-box shadow-sm">
                            <div class="formula-title">GRADING FORMULA</div>
                            <div class="formula-content">
                                <table class="w-100 text-start table-borderless" style="font-size: 0.65rem;">
                                    <tr><td><strong>A-1</strong></td><td>80% & above</td></tr>
                                    <tr><td><strong>A</strong></td><td>70% to 79%</td></tr>
                                    <tr><td><strong>B</strong></td><td>60% to 69%</td></tr>
                                    <tr><td><strong>C</strong></td><td>50% to 59%</td></tr>
                                    <tr><td><strong>D</strong></td><td>40% to 49%</td></tr>
                                    <tr><td><strong>E</strong></td><td>33% to 39%</td></tr>
                                    <tr><td><strong>F</strong></td><td>Below 33% (Fail)</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STUDENT PROFILE INFO -->
            @php
                $className = $marksheet->student->class->name;
                $classParts = preg_split('/[\s\-\/]/', $className, 2);
                $classDisplay = $classParts[0] ?? $className;
                $sectionDisplay = $classParts[1] ?? 'B';
            @endphp
            
            <div class="row details-row mb-4">
                <div class="col-md-6 mb-2">
                    <span class="details-label">G.R. No:</span>
                    <span class="details-value" style="width: calc(100% - 85px);">{{ $marksheet->student->admission_number }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="details-label">Seat No:</span>
                    <span class="details-value" style="width: calc(100% - 85px);">{{ $marksheet->student->roll_number }}</span>
                </div>
                
                <div class="col-md-6 mb-2">
                    <span class="details-label">Mr. / Miss:</span>
                    <span class="details-value" style="width: calc(100% - 100px); text-transform: uppercase;">{{ $marksheet->student->name }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="details-label">S/o, D/o:</span>
                    <span class="details-value" style="width: calc(100% - 90px); text-transform: uppercase;">{{ $marksheet->student->father_name }}</span>
                </div>
                
                <div class="col-md-6 mb-2">
                    <span class="details-label">Class:</span>
                    <span class="details-value" style="width: calc(100% - 75px);">{{ $classDisplay }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="details-label">Section:</span>
                    <span class="details-value" style="width: calc(100% - 90px);">{{ $sectionDisplay }}</span>
                </div>
                
                <div class="col-12">
                    <span class="details-label">In each head of passing at the quarterly / terminal. Examination held in the month of:</span>
                    <span class="details-value" style="width: calc(100% - 620px); text-align: center;">{{ $marksheet->exam->name }}</span>
                </div>
            </div>

            <!-- DETAILED SUBJECT MARKS TABLE -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle marks-table">
                    <thead>
                        <tr>
                            <th class="text-start" style="width: 30%;">Subject</th>
                            <th style="width: 15%;">Maximum Marks</th>
                            <th style="width: 15%;">Minimum Marks</th>
                            <th style="width: 15%;">Marks Obtained</th>
                            <th style="width: 15%;">Remarks</th>
                            <th style="width: 10%;">Grade</th>
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
                                <td class="fw-semibold">{{ $subMark->total_marks }}</td>
                                <td>{{ $subMark->passing_marks }}</td>
                                <td class="fw-bold {{ !$isPassed ? 'text-danger' : 'text-dark' }}">{{ $subMark->obtained_marks }}</td>
                                <td class="fw-semibold {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                    {{ $isPassed ? 'Pass' : 'Fail' }}
                                </td>
                                <td class="fw-bold">{{ $subGrade }}</td>
                            </tr>
                        @endforeach
                        
                        <!-- Total Row -->
                        <tr class="fw-bold border-top border-dark" style="background-color: #f1f5f9;">
                            <td class="text-start text-primary">TOTAL</td>
                            <td style="color: #1e3a8a;">{{ $marksheet->total_marks }}</td>
                            <td>-</td>
                            <td style="color: #1e3a8a; font-size: 1.05rem;">{{ $marksheet->obtained_marks }}</td>
                            <td class="{{ $marksheet->result === 'PASS' ? 'text-success' : 'text-danger' }}">{{ $marksheet->result }}</td>
                            <td class="text-warning">{{ $marksheet->grade }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PERFORMANCE SUMMARY BOTTOM INFO -->
            <div class="row align-items-end mt-4">
                <div class="col-md-9">
                    <div class="summary-text-line">
                        <span>Total Marks in words:</span>
                        <span class="value-underline" style="width: calc(100% - 165px);">{{ $marksheet->obtained_marks_in_words }} Only</span>
                    </div>
                    
                    <div class="summary-text-line">
                        <span>Percentage:</span>
                        <span class="value-underline" style="width: 150px; text-align: center;">{{ $marksheet->percentage }}%</span>
                        
                        <span class="ms-4">Position:</span>
                        <span class="value-underline" style="width: 150px; text-align: center;">{{ $marksheet->result }}</span>
                    </div>
                    
                    <div class="summary-text-line">
                        <span>Attended Days:</span>
                        <span class="value-underline" style="width: 120px; text-align: center;">______</span>
                        <span>Out of:</span>
                        <span class="value-underline" style="width: 120px; text-align: center;">______</span>
                    </div>
                </div>
                
                <!-- Division Formula -->
                <div class="col-md-3">
                    <div class="formula-box shadow-sm mb-2">
                        <div class="formula-title">DIVISION FORMULA</div>
                        <div class="formula-content">
                            <table class="w-100 text-start table-borderless" style="font-size: 0.65rem;">
                                <tr><td><strong>First Class:</strong></td><td>60% & above</td></tr>
                                <tr><td><strong>Second Class:</strong></td><td>45% to 59%</td></tr>
                                <tr><td><strong>Third Class:</strong></td><td>33% to 44%</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTE -->
            <p class="school-note">
                <strong>Note:</strong> The School reserves the right of issuing any correction in the results if any mistake is detected later on.
            </p>

            <!-- SIGNATURES FOOTER -->
            <div class="row signature-area text-center">
                <div class="col-6">
                    <div class="sig-line"></div>
                    <div class="sig-title">Class Teacher Sig</div>
                </div>
                <div class="col-6">
                    <div class="sig-line"></div>
                    <div class="sig-title">PRINCIPAL</div>
                    <div style="font-size: 0.72rem; color: #475569; margin-top: 3px;">
                        ({{ $school->principal_name ?? 'Prof. Ghulam Rasool' }})
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
