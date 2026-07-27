@extends('layouts.app')

@section('title', 'Class Wise Results')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Class-Wise Academic Results</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marksheets.index') }}">Marksheets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Class Results</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Class Result Selection Card -->
<div class="card-box mb-4">
    <form action="{{ route('marksheets.class-wise') }}" method="GET" class="row g-3 align-items-end">
        <!-- Class -->
        <div class="col-12 col-sm-6 col-md-3">
            <label for="class_id" class="form-label">Select Class</label>
            <select name="class_id" id="class_id" class="form-select" required>
                <option value="">Select Class...</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $classId == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Exam -->
        <div class="col-12 col-sm-6 col-md-3">
            <label for="exam_id" class="form-label">Select Exam</label>
            <select name="exam_id" id="exam_id" class="form-select" required>
                <option value="">Select Exam...</option>
                @foreach($exams as $ex)
                    <option value="{{ $ex->id }}" {{ $examId == $ex->id ? 'selected' : '' }}>{{ $ex->name }} ({{ $ex->academic_session }})</option>
                @endforeach
            </select>
        </div>

        <!-- Session -->
        <div class="col-12 col-sm-6 col-md-3">
            <label for="academic_session" class="form-label">Academic Session</label>
            <input type="text" name="academic_session" id="academic_session" class="form-control" value="{{ $session ?? $defaultSession }}" required>
        </div>

        <!-- Submit -->
        <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-list-check me-2"></i>Load Results</button>
            @if($classId && $examId && count($marksheets) > 0)
                <a href="{{ route('marksheets.class-wise.pdf', ['class_id' => $classId, 'exam_id' => $examId, 'academic_session' => $session]) }}" class="btn btn-outline-danger"><i class="fa-solid fa-file-pdf"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Results Table (Only shows if selected and populated) -->
@if($classId && $examId)
    <div class="card-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-primary mb-0"><i class="fa-solid fa-graduation-cap me-2"></i>Class Summary Sheet</h5>
            @if(count($marksheets) > 0)
                <div class="text-muted small">Total Generated: <strong>{{ count($marksheets) }} Marksheets</strong></div>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle text-center">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th class="text-start">Student Name</th>
                        <th>Admission No</th>
                        <th>Total Max</th>
                        <th>Obtained Max</th>
                        <th>Percentage</th>
                        <th>Overall Grade</th>
                        <th>Result Status</th>
                        <th class="text-end" style="min-width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marksheets as $ms)
                        <tr>
                            <td><span class="badge bg-light text-dark border px-2.5 py-1.5">{{ $ms->student->roll_number }}</span></td>
                            <td class="text-start fw-semibold">{{ $ms->student->name }}</td>
                            <td><code class="text-dark">{{ $ms->student->admission_number }}</code></td>
                            <td>{{ $ms->total_marks }}</td>
                            <td class="fw-bold text-primary">{{ $ms->obtained_marks }}</td>
                            <td class="fw-bold">{{ $ms->percentage }}%</td>
                            <td><span class="badge bg-light text-dark border">{{ $ms->grade }}</span></td>
                            <td>
                                @if($ms->result === 'PASS')
                                    <span class="badge bg-success text-white px-2.5 py-1.5">PASS</span>
                                @else
                                    <span class="badge bg-danger text-white px-2.5 py-1.5">FAIL</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('marksheets.show', $ms->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Detail Marksheet">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                <a href="{{ route('marksheets.pdf', $ms->id) }}" class="btn btn-outline-secondary btn-sm" title="Download Marksheet PDF">
                                    <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No student marksheets have been submitted for this exam session. Go to 'Enter New Marks'.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
