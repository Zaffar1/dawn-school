@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Welcome, {{ Auth::user()->name }}!</h3>
        <p class="text-muted mb-0">Here is what's happening at {{ $school->name ?? 'SUPER DAWN SCHOOL LAKHI' }} today.</p>
    </div>
    <div class="text-end">
        <div class="fw-semibold text-secondary"><i class="fa-regular fa-calendar-days me-1"></i> {{ date('F d, Y') }}</div>
    </div>
</div>

<!-- 1. DASHBOARD STAT CARDS -->
<div class="row g-4 mb-4">
    <!-- Active Students (Student count must show ACTIVE students only) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-primary">
            <div class="icon-box">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div class="stat-value">{{ number_format($activeStudentsCount) }}</div>
            <div class="stat-label">Active Students</div>
        </div>
    </div>

    <!-- Inactive Students -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-danger">
            <div class="icon-box">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div class="stat-value">{{ number_format($inactiveStudentsCount) }}</div>
            <div class="stat-label">Inactive Students</div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-info">
            <div class="icon-box">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="stat-value">{{ number_format($classesCount) }}</div>
            <div class="stat-label">Total Classes</div>
        </div>
    </div>

    <!-- Today's Collection -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-success">
            <div class="icon-box">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($todayCollection, 2) }}</div>
            <div class="stat-label">Today's Collection</div>
        </div>
    </div>

    <!-- This Month's Collection -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-success">
            <div class="icon-box">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($monthCollection, 2) }}</div>
            <div class="stat-label">This Month's Collection</div>
        </div>
    </div>

    <!-- Total Arrears -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-warning">
            <div class="icon-box">
                <i class="fa-solid fa-scale-unbalanced"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalArrears, 2) }}</div>
            <div class="stat-label">Total Arrears</div>
        </div>
    </div>

    <!-- Total Admissions -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-stat stat-primary">
            <div class="icon-box">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="stat-value">{{ number_format($totalAdmissions) }}</div>
            <div class="stat-label">Total Admissions</div>
        </div>
    </div>
</div>

<!-- 2. QUICK ACTIONS BANNER -->
<div class="card-box mb-4">
    <h5 class="mb-3 text-primary"><i class="fa-solid fa-bolt me-2"></i>Quick Actions</h5>
    <div class="row g-3">
        @can('manage-admissions')
        <div class="col-6 col-md-3">
            <a href="{{ route('admissions.create') }}" class="quick-action-btn shadow-sm">
                <span>Add Admission</span>
                <i class="fa-solid fa-user-plus text-primary"></i>
            </a>
        </div>
        @endcan

        @can('manage-students')
        <div class="col-6 col-md-3">
            <a href="{{ route('students.index') }}" class="quick-action-btn shadow-sm">
                <span>Student Directory</span>
                <i class="fa-solid fa-user-graduate text-success"></i>
            </a>
        </div>
        @endcan

        @can('manage-fee-collection')
        <div class="col-6 col-md-3">
            <a href="{{ route('fee-collection.create') }}" class="quick-action-btn shadow-sm">
                <span>Collect Fee</span>
                <i class="fa-solid fa-circle-dollar-to-slot text-warning"></i>
            </a>
        </div>
        @endcan

        @can('manage-marksheets')
        <div class="col-6 col-md-3">
            <a href="{{ route('marksheets.create') }}" class="quick-action-btn shadow-sm">
                <span>Marks Entry</span>
                <i class="fa-solid fa-id-card-clip text-info"></i>
            </a>
        </div>
        @endcan
    </div>
</div>

<!-- 3. VISUAL CHARTS SECTION -->
<div class="row g-4">
    <!-- Chart 1: Monthly Fee Collection -->
    <div class="col-12 col-xl-8">
        <div class="card-box h-100 pb-2">
            <h5 class="card-title text-secondary mb-3"><i class="fa-solid fa-chart-area me-2"></i>Monthly Fee Collection</h5>
            <div style="height: 300px;">
                <canvas id="monthlyCollectionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3: Fee Summary Category Breakdown -->
    <div class="col-12 col-xl-4">
        <div class="card-box h-100 pb-2">
            <h5 class="card-title text-secondary mb-3"><i class="fa-solid fa-chart-pie me-2"></i>Fee Collection Summary</h5>
            <div style="height: 230px; position: relative;">
                <canvas id="feeSummaryChart"></canvas>
            </div>
            <div class="text-center mt-3 small">
                <span class="badge text-dark bg-light p-2 mb-1">Total Cash: Rs. {{ number_format($feeSummary['total'], 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Chart 2: Class-wise Student Count -->
    <div class="col-12">
        <div class="card-box pb-2">
            <h5 class="card-title text-secondary mb-3"><i class="fa-solid fa-chart-column me-2"></i>Class-wise Student Distribution (Active Students)</h5>
            <div style="height: 280px;">
                <canvas id="classStudentChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Colors palette
        const primaryColor = '#1e3a8a';
        const primaryLight = '#3b82f6';
        const accentColor = '#38bdf8';
        const successColor = '#10b981';
        const warningColor = '#f59e0b';
        const dangerColor = '#ef4444';
        const muteColor = '#94a3b8';

        // 1. Chart: Monthly Collection (Line Chart)
        const ctxMonthly = document.getElementById('monthlyCollectionChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyChartLabels) !!},
                datasets: [{
                    label: 'Collection (Rs.)',
                    data: {!! json_encode($monthlyChartValues) !!},
                    borderColor: successColor,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: successColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: muteColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: muteColor }
                    }
                }
            }
        });

        // 2. Chart: Class-wise Students (Bar Chart)
        const ctxClass = document.getElementById('classStudentChart').getContext('2d');
        new Chart(ctxClass, {
            type: 'bar',
            data: {
                labels: {!! json_encode($classChartLabels) !!},
                datasets: [{
                    label: 'Students Count',
                    data: {!! json_encode($classChartValues) !!},
                    backgroundColor: primaryLight,
                    hoverBackgroundColor: primaryColor,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 1, color: muteColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: muteColor }
                    }
                }
            }
        });

        // 3. Chart: Fee breakdown (Doughnut)
        const ctxFee = document.getElementById('feeSummaryChart').getContext('2d');
        new Chart(ctxFee, {
            type: 'doughnut',
            data: {
                labels: ['Admission Fee', 'Monthly Fee', 'Exam Fee', 'Arrears Portion'],
                datasets: [{
                    data: [
                        {{ $feeSummary['admission'] }},
                        {{ $feeSummary['monthly'] }},
                        {{ $feeSummary['exam'] }},
                        {{ $feeSummary['arrears'] }}
                    ],
                    backgroundColor: [primaryColor, primaryLight, accentColor, warningColor],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: { size: 11 },
                            color: '#475569'
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
