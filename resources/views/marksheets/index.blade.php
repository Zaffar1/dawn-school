@extends('layouts.app')

@section('title', 'Academic Marksheets')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Academic Marksheets</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Marksheets</li>
            </ol>
        </nav>
    </div>
    @can('manage-marksheets')
    <div class="text-end">
        <a href="{{ route('marksheets.create') }}" class="btn btn-primary"><i class="fa-solid fa-file-signature me-2"></i>Enter New Marks</a>
    </div>
    @endcan
</div>

<!-- Filters Toolbar -->
<div class="card-box mb-4">
    <form action="{{ route('marksheets.index') }}" method="GET" class="row g-3 align-items-end">
        <!-- Class Filter -->
        <div class="col-12 col-sm-6 col-md-4">
            <label for="class_id" class="form-label">Filter by Class</label>
            <select name="class_id" id="class_id" class="form-select">
                <option value="">All Classes</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Exam Filter -->
        <div class="col-12 col-sm-6 col-md-4">
            <label for="exam_id" class="form-label">Filter by Exam</label>
            <select name="exam_id" id="exam_id" class="form-select">
                <option value="">All Exams</option>
                @foreach($exams as $ex)
                    <option value="{{ $ex->id }}" {{ $examFilter == $ex->id ? 'selected' : '' }}>{{ $ex->name }} ({{ $ex->academic_session }})</option>
                @endforeach
            </select>
        </div>

        <!-- Actions -->
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            <a href="{{ route('marksheets.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Marksheets List Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Exam Name</th>
                    <th>Session</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Obtained</th>
                    <th class="text-center">Percentage</th>
                    <th class="text-center">Grade</th>
                    <th>Result</th>
                    <th class="text-end" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marksheets as $index => $ms)
                    <tr>
                        <td>{{ $marksheets->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('students.show', $ms->student->id) }}" class="fw-semibold text-decoration-none">
                                {{ $ms->student->name }}
                            </a>
                            <div class="text-muted small">S/O: {{ $ms->student->father_name }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $ms->student->roll_number }}</span></td>
                        <td><span class="fw-semibold">{{ $ms->exam->name }}</span></td>
                        <td><code>{{ $ms->academic_session }}</code></td>
                        <td class="text-center">{{ $ms->total_marks }}</td>
                        <td class="text-center fw-medium">{{ $ms->obtained_marks }}</td>
                        <td class="text-center fw-bold">{{ $ms->percentage }}%</td>
                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $ms->grade }}</span></td>
                        <td>
                            @if($ms->result === 'PASS')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5">PASS</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5">FAIL</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('marksheets.show', $ms->id) }}" class="btn btn-outline-info btn-sm me-1" title="View details">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <a href="{{ route('marksheets.pdf', $ms->id) }}" class="btn btn-outline-secondary btn-sm" title="Download A4 PDF">
                                <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">No academic marksheets generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $marksheets->links() }}
    </div>
</div>
@endsection
