<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $school_name ?? 'SUPER DAWN SCHOOL LAKHI' }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Application CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>

    @php
        $school = \App\Models\School::first();
        $school_name = $school ? $school->name : 'DAWN PUBLIC SCHOOL / SUPER DAWN SCHOOL SYSTEM LAKHI';
        if ($school_name === 'SUPER DAWN SCHOOL LAKHI') {
            $school_name = 'DAWN PUBLIC SCHOOL / SUPER DAWN SCHOOL SYSTEM LAKHI';
        }
        $school_session = $school ? $school->academic_session : '2026-2027';
    @endphp

    <!-- 1. TOP HEADER -->
    <header class="top-header d-flex align-items-center justify-content-between">
        <!-- Left Side: Burger Menu & Session Badge -->
        <div class="d-flex align-items-center gap-3" style="flex: 1; min-width: 0;">
            <button class="btn text-white d-lg-none p-0 border-0 me-1" id="mobile-toggle" aria-label="Toggle Navigation">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>
            <span class="session-badge"><i class="fa-solid fa-graduation-cap me-1"></i> Session: {{ $school_session }}</span>
        </div>
        
        <!-- Center: Centered School Name -->
        <div class="text-center d-none d-md-block" style="flex: 2; min-width: 0;">
            <h1 class="brand-title m-0 text-white fw-bold fs-5 text-truncate">{{ $school_name }}</h1>
        </div>
        
        <!-- Right Side: User Dropdown -->
        <div class="user-area d-flex align-items-center justify-content-end" style="flex: 1; min-width: 0;">
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2 p-1 text-white border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Profile circular initials -->
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: rgba(255,255,255,0.15); color: #ffffff;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <!-- Name & Role (Hidden on mobile) -->
                    <div class="text-start d-none d-md-block me-1">
                        <div class="fw-semibold text-white lh-1 small">{{ Auth::user()->name }}</div>
                        <span class="text-white-50" style="font-size: 0.7rem;">{{ Auth::user()->role->name }}</span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 py-2" style="border-radius: 12px; min-width: 180px;">
                    <li><h6 class="dropdown-header text-dark fw-bold pb-1">{{ Auth::user()->name }}</h6></li>
                    <li><span class="dropdown-item-text text-muted small pt-0 mb-1">Role: <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5">{{ Auth::user()->role->name }}</span></span></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    @can('manage-settings')
                        <li><a class="dropdown-item py-2" href="{{ route('settings.index') }}"><i class="fa-solid fa-sliders me-2 text-muted"></i>Settings</a></li>
                    @endcan
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- 2. SIDEBAR & CONTENT CONTAINER -->
    <div id="wrapper">
        
        <!-- Sidebar Navigation -->
        <nav id="sidebar" class="collapse-mobile">
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Admissions (Admin, Super Admin) -->
                @can('manage-admissions')
                <li class="{{ Request::routeIs('admissions.*') ? 'active' : '' }}">
                    <a href="{{ route('admissions.index') }}">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Admissions</span>
                    </a>
                </li>
                @endcan

                <!-- Students (Super Admin, Admin, Teacher) -->
                @can('manage-students')
                <li class="{{ Request::routeIs('students.*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                @endcan

                <!-- Classes (Admin, Super Admin) -->
                @can('manage-classes')
                <li class="{{ Request::routeIs('classes.*') ? 'active' : '' }}">
                    <a href="{{ route('classes.index') }}">
                        <i class="fa-solid fa-school"></i>
                        <span>Classes</span>
                    </a>
                </li>
                @endcan

                <!-- Subjects (Super Admin, Admin, Teacher) -->
                @can('manage-subjects')
                <li class="{{ Request::routeIs('subjects.*') ? 'active' : '' }}">
                    <a href="{{ route('subjects.index') }}">
                        <i class="fa-solid fa-book"></i>
                        <span>Subjects</span>
                    </a>
                </li>
                @endcan

                <!-- Exams (Super Admin, Teacher) -->
                @can('manage-exams')
                <li class="{{ Request::routeIs('exams.*') ? 'active' : '' }}">
                    <a href="{{ route('exams.index') }}">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Exams</span>
                    </a>
                </li>
                @endcan

                <!-- Fee Settings (Super Admin, Accountant) -->
                @can('manage-fee-settings')
                <li class="{{ Request::routeIs('fee-settings.*') ? 'active' : '' }}">
                    <a href="{{ route('fee-settings.index') }}">
                        <i class="fa-solid fa-gears"></i>
                        <span>Fee Settings</span>
                    </a>
                </li>
                @endcan

                <!-- Fee Collection (Super Admin, Accountant) -->
                @can('manage-fee-collection')
                <li class="{{ Request::routeIs('fee-collection.*') ? 'active' : '' }}">
                    <a href="{{ route('fee-collection.index') }}">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Fee Collection</span>
                    </a>
                </li>
                @endcan

                <!-- Receipts (Super Admin, Accountant) -->
                @can('manage-receipts')
                <li class="{{ Request::routeIs('receipts.*') ? 'active' : '' }}">
                    <a href="{{ route('receipts.index') }}">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Receipts</span>
                    </a>
                </li>
                @endcan

                <!-- Marksheets (Super Admin, Teacher) -->
                @can('manage-marksheets')
                <li class="{{ Request::routeIs('marksheets.*') ? 'active' : '' }}">
                    <a href="{{ route('marksheets.index') }}">
                        <i class="fa-solid fa-id-card-clip"></i>
                        <span>Marksheets</span>
                    </a>
                </li>
                @endcan

                <!-- Class-wise Result (Available for viewing class marksheet sheets) -->
                <li class="{{ Request::routeIs('marksheets.class-wise') ? 'active' : '' }}">
                    <a href="{{ route('marksheets.class-wise') }}">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Class Results</span>
                    </a>
                </li>

                <!-- Reports (Super Admin, Admin, Accountant) -->
                @can('view-reports')
                <li class="{{ Request::routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endcan

                <!-- User Management (Super Admin only) -->
                @can('manage-users')
                <li class="{{ Request::routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Users</span>
                    </a>
                </li>
                @endcan

                <!-- Hostel Management (Super Admin only) -->
                @can('manage-hostel')
                @php
                    $isHostelActive = Request::routeIs('hostel.*');
                @endphp
                <li class="{{ $isHostelActive ? 'active' : '' }}">
                    <a href="#hostelSubmenu" data-bs-toggle="collapse" class="{{ $isHostelActive ? '' : 'collapsed' }}" aria-expanded="{{ $isHostelActive ? 'true' : 'false' }}">
                        <i class="fa-solid fa-hotel text-white-50"></i>
                        <span>Sukkur Hostel</span>
                    </a>
                    <ul id="hostelSubmenu" class="collapse list-unstyled {{ $isHostelActive ? 'show' : '' }}">
                        <li class="{{ Request::routeIs('hostel.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('hostel.dashboard') }}">
                                <i class="fa-solid fa-chart-line me-1" style="font-size:0.8rem;"></i> Dashboard
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.residents.*') ? 'active' : '' }}">
                            <a href="{{ route('hostel.residents.index') }}">
                                <i class="fa-solid fa-users me-1" style="font-size:0.8rem;"></i> Students
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.resident-fees.*') ? 'active' : '' }}">
                            <a href="{{ route('hostel.resident-fees.index') }}">
                                <i class="fa-solid fa-file-invoice-dollar me-1" style="font-size:0.8rem;"></i> Fee Collection
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.index') && request()->route('category') === 'expenditures' ? 'active' : '' }}">
                            <a href="{{ route('hostel.index', 'expenditures') }}">
                                <i class="fa-solid fa-money-bill-wave me-1" style="font-size:0.8rem;"></i> Expenditures
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.index') && request()->route('category') === 'salaries' ? 'active' : '' }}">
                            <a href="{{ route('hostel.index', 'salaries') }}">
                                <i class="fa-solid fa-handshake me-1" style="font-size:0.8rem;"></i> Salaries
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.index') && request()->route('category') === 'rent' ? 'active' : '' }}">
                            <a href="{{ route('hostel.index', 'rent') }}">
                                <i class="fa-solid fa-house-chimney me-1" style="font-size:0.8rem;"></i> Rent
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.index') && request()->route('category') === 'electric-bill' ? 'active' : '' }}">
                            <a href="{{ route('hostel.index', 'electric-bill') }}">
                                <i class="fa-solid fa-bolt me-1" style="font-size:0.8rem;"></i> Electric Bill
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('hostel.index') && request()->route('category') === 'other' ? 'active' : '' }}">
                            <a href="{{ route('hostel.index', 'other') }}">
                                <i class="fa-solid fa-coins me-1" style="font-size:0.8rem;"></i> Other Exp.
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Settings (Super Admin, Settings permission) -->
                @can('manage-settings')
                <li class="{{ Request::routeIs('settings.index') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Settings</span>
                    </a>
                </li>
                @endcan

                <!-- Logout Link -->
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="text-danger-hover">
                        <i class="fa-solid fa-right-from-bracket text-danger"></i>
                        <span class="text-danger">Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <main id="content-wrapper">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
        
    </div>

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar mobile toggler JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileToggle = document.getElementById('mobile-toggle');
            const sidebar = document.getElementById('sidebar');

            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show-mobile');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function (e) {
                    if (window.innerWidth < 992) {
                        if (!sidebar.contains(e.target) && e.target !== mobileToggle && !mobileToggle.contains(e.target)) {
                            sidebar.classList.remove('show-mobile');
                        }
                    }
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
