@extends('layouts.app')

@section('title', 'Hostel Residents Directory')

@section('content')
<div class="page-title-box d-flex align-items-center justify-content-between">
    <div>
        <h3 class="mb-1"><i class="fa-solid fa-users me-2 text-primary"></i>Hostel Residents</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostel.dashboard') }}">Sukkur Hostel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Residents Directory</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('hostel.residents.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-1"></i> Register Resident / Staff
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-success-subtle text-success">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="stat-value">{{ $totalActive }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-danger-subtle text-danger">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div class="stat-value">{{ $totalInactive }}</div>
            <div class="stat-label">Inactive/Left Residents</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat bg-white">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalCollectedThisMonth, 2) }}</div>
            <div class="stat-label">Fees Collected (This Month)</div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card-box mb-4">
    <form method="GET" action="{{ route('hostel.residents.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label small text-muted fw-semibold">Search Resident</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, room number, or phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Residents</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive/Left</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            </div>
        </div>
    </form>
</div>

<!-- Residents Directory Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Resident Name</th>
                    <th>Resident Type</th>
                    <th>Room No.</th>
                    <th>Phone</th>
                    <th>Fee / Salary</th>
                    <th>Joining Date</th>
                    <th>Leaving Date</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $res)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-small me-2 bg-light rounded text-center d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                    <i class="fa-solid fa-user text-muted"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark">{{ $res->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($res->resident_type === 'resident')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-semibold">
                                    Hostel Resident
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 fw-semibold">
                                    Hostel Staff
                                </span>
                            @endif
                        </td>
                        <td><span class="fw-bold text-dark"><i class="fa-solid fa-door-open me-1 text-muted"></i>{{ $res->room_number }}</span></td>
                        <td>{{ $res->phone ?? '-' }}</td>
                        <td><span class="fw-semibold text-dark">Rs. {{ number_format($res->monthly_fee, 2) }}</span></td>
                        <td>{{ $res->joining_date->format('d-M-Y') }}</td>
                        <td>{{ $res->leaving_date ? $res->leaving_date->format('d-M-Y') : '-' }}</td>
                        <td>
                            @if($res->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-semibold">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-semibold">Left</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                @if($res->status === 'active')
                                    <a href="{{ route('hostel.resident-fees.create', ['resident_id' => $res->id]) }}" class="btn btn-outline-success btn-sm" title="Collect fee payment">
                                        <i class="fa-solid fa-hand-holding-dollar"></i> Collect Fee
                                    </a>
                                @endif
                                <a href="{{ route('hostel.residents.edit', $res->id) }}" class="btn btn-outline-primary btn-sm ms-1" title="Edit details">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('hostel.residents.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this resident? All associated fee payments will also be deleted.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm ms-1" title="Delete resident">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-user-group fs-2 mb-3 d-block text-black-50"></i>
                            No residents registered in the system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $residents->links() }}
    </div>
</div>
@endsection
