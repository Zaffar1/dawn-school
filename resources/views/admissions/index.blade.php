@extends('layouts.app')

@section('title', 'Admissions Log')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Admissions Directory</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admissions</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('admissions.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus me-2"></i>New Admission</a>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Admission Number</th>
                    <th>Class</th>
                    <th>Admission Date</th>
                    <th class="text-end">Admission Fee</th>
                    <th class="text-end">Monthly Fee</th>
                    <th class="text-end">Arrears</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $index => $adm)
                    <tr>
                        <td>{{ $admissions->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('students.show', $adm->student->id) }}" class="fw-semibold text-decoration-none">
                                {{ $adm->student->name }}
                            </a>
                            <div class="text-muted small">S/O: {{ $adm->student->father_name }}</div>
                        </td>
                        <td><code class="text-dark fw-semibold">{{ $adm->student->admission_number }}</code></td>
                        <td><span class="badge bg-light text-primary border">{{ $adm->class->name }}</span></td>
                        <td>{{ $adm->admission_date->format('d-M-Y') }}</td>
                        <td class="text-end fw-semibold">Rs. {{ number_format($adm->admission_fee, 2) }}</td>
                        <td class="text-end">Rs. {{ number_format($adm->monthly_fee, 2) }}</td>
                        <td class="text-end text-warning fw-semibold">Rs. {{ number_format($adm->arrears, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admissions.show', $adm->id) }}" class="btn btn-outline-info btn-sm me-1" title="View details">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <a href="{{ route('admissions.pdf', $adm->id) }}" class="btn btn-outline-secondary btn-sm" title="Download Form PDF">
                                <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No admission logs found. Click 'New Admission' to register.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $admissions->links() }}
    </div>
</div>
@endsection
