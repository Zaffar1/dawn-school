<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeeSettingController;
use App\Http\Controllers\FeeCollectionController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\MarksheetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\HostelResidentController;
use App\Http\Controllers\HostelResidentFeeController;
use App\Http\Controllers\ArrearsController;

// Redirect root to dashboard/login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard & Settings
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index')->middleware('can:manage-settings');
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update')->middleware('can:manage-settings');

    // Admissions
    Route::middleware('can:manage-admissions')->group(function () {
        Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
        Route::get('/admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
        Route::post('/admissions', [AdmissionController::class, 'store'])->name('admissions.store');
        Route::get('/admissions/{id}', [AdmissionController::class, 'show'])->name('admissions.show');
        Route::get('/admissions/{id}/pdf', [AdmissionController::class, 'pdf'])->name('admissions.pdf');
    });

    // Students
    Route::middleware('can:manage-students')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::post('/students/{id}/deactivate', [StudentController::class, 'deactivate'])->name('students.deactivate');
        Route::post('/students/{id}/reactivate', [StudentController::class, 'reactivate'])->name('students.reactivate');
        // NOTE: Strictly no DELETE students route is declared here!
    });

    // Classes
    Route::middleware('can:manage-classes')->group(function () {
        Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{id}/edit', [ClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
        Route::get('/classes/{id}/students', [ClassController::class, 'students'])->name('classes.students');
    });

    // Subjects
    Route::middleware('can:manage-subjects')->group(function () {
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    });

    // Exams
    Route::middleware('can:manage-exams')->group(function () {
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');
    });

    // Fee Settings
    Route::middleware('can:manage-fee-settings')->group(function () {
        Route::get('/fee-settings', [FeeSettingController::class, 'index'])->name('fee-settings.index');
        Route::post('/fee-settings', [FeeSettingController::class, 'update'])->name('fee-settings.update');
    });

    // Fee Collection
    Route::middleware('can:manage-fee-collection')->group(function () {
        Route::get('/fee-collection', [FeeCollectionController::class, 'index'])->name('fee-collection.index');
        Route::get('/fee-collection/create', [FeeCollectionController::class, 'create'])->name('fee-collection.create');
        Route::post('/fee-collection', [FeeCollectionController::class, 'store'])->name('fee-collection.store');
        Route::get('/fee-collection/student/{id}', [FeeCollectionController::class, 'getStudentFees'])->name('fee-collection.student-fees');
    });

    // Arrears Management
    Route::middleware('can:manage-arrears')->group(function () {
        Route::get('/arrears', [ArrearsController::class, 'index'])->name('arrears.index');
        Route::post('/arrears/collect', [ArrearsController::class, 'collectPayment'])->name('arrears.collect');
    });

    // Receipts
    Route::middleware('can:manage-receipts')->group(function () {
        Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
        Route::get('/receipts/{id}', [ReceiptController::class, 'show'])->name('receipts.show');
        Route::get('/receipts/{id}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
    });

    // Marksheets & Academic Entries
    Route::get('/marksheets/students/{class_id}', [MarksheetController::class, 'getStudentsForExam'])->name('marksheets.get-students');
    Route::get('/marksheets/subjects/{class_id}', [MarksheetController::class, 'getSubjectsForClass'])->name('marksheets.get-subjects');
    Route::get('/marksheets/class-wise', [MarksheetController::class, 'classWise'])->name('marksheets.class-wise');
    Route::get('/marksheets/class-wise/pdf', [MarksheetController::class, 'classWisePdf'])->name('marksheets.class-wise.pdf');

    Route::middleware('can:manage-marksheets')->group(function () {
        Route::get('/marksheets', [MarksheetController::class, 'index'])->name('marksheets.index');
        Route::get('/marksheets/create', [MarksheetController::class, 'create'])->name('marksheets.create');
        Route::post('/marksheets', [MarksheetController::class, 'store'])->name('marksheets.store');
        Route::get('/marksheets/{id}', [MarksheetController::class, 'show'])->name('marksheets.show');
        Route::get('/marksheets/{id}/pdf', [MarksheetController::class, 'pdf'])->name('marksheets.pdf');
    });

    // Reports
    Route::middleware('can:view-reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    // User Management
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    });

    // Hostel Management (Super Admin only via manage-hostel Gate)
    Route::middleware(['can:manage-hostel'])->prefix('hostel')->name('hostel.')->group(function () {
        // Hostel Dashboard
        Route::get('dashboard', [HostelController::class, 'dashboard'])->name('dashboard');

        // Residents
        Route::resource('residents', HostelResidentController::class)->except(['show']);

        // Resident Fees
        Route::resource('resident-fees', HostelResidentFeeController::class)->except(['show']);
        Route::get('resident-fees/{id}/receipt', [HostelResidentFeeController::class, 'receipt'])->name('resident-fees.receipt');

        // Expenditures/Costs
        Route::get('{category}', [HostelController::class, 'index'])->name('index');
        Route::get('{category}/create', [HostelController::class, 'create'])->name('create');
        Route::post('{category}', [HostelController::class, 'store'])->name('store');
        Route::get('{category}/{id}/edit', [HostelController::class, 'edit'])->name('edit');
        Route::put('{category}/{id}', [HostelController::class, 'update'])->name('update');
        Route::delete('{category}/{id}', [HostelController::class, 'destroy'])->name('destroy');
    });
});
