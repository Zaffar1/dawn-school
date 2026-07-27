@extends('layouts.app')

@section('title', 'Enter Examination Marks')

@section('content')
<div class="page-title-box">
    <div>
        <h3>Enter Examination Marks</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marksheets.index') }}">Marksheets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Enter Marks</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <a href="{{ route('marksheets.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
    </div>
</div>

<div class="card-box">
    <h5 class="text-primary mb-4 border-bottom pb-2"><i class="fa-solid fa-file-signature me-2"></i>Academic Score Entry</h5>
    
    <form action="{{ route('marksheets.store') }}" method="POST">
        @csrf
        
        <!-- Selection block -->
        <div class="row g-3 mb-4">
            <!-- Select Class -->
            <div class="col-12 col-md-4">
                <label for="class_id" class="form-label">Select Class</label>
                <select id="class_id" class="form-select" required>
                    <option value="">Select class...</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Exam -->
            <div class="col-12 col-md-4">
                <label for="exam_id" class="form-label">Select Examination</label>
                <select name="exam_id" id="exam_id" class="form-select" required>
                    <option value="">Select exam...</option>
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" data-class-id="{{ $ex->class_id }}" class="exam-option d-none">{{ $ex->name }} ({{ $ex->academic_session }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Student -->
            <div class="col-12 col-md-4">
                <label for="student_id" class="form-label">Select Student (Active Only)</label>
                <select name="student_id" id="student_id" class="form-select" required disabled>
                    <option value="">Choose class first...</option>
                </select>
            </div>

            <input type="hidden" name="academic_session" id="academic_session" value="{{ $defaultSession }}">
        </div>

        <!-- Dynamic Marks Entry Grid (Initially Hidden) -->
        <div id="marks-entry-section" class="d-none">
            <h6 class="text-secondary fw-bold uppercase mb-3"><i class="fa-solid fa-list-check me-2"></i>Subject-wise Obtained Scores</h6>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light text-secondary small uppercase">
                        <tr>
                            <th class="text-start">Subject Name</th>
                            <th>Total Maximum Marks</th>
                            <th>Passing Threshold</th>
                            <th style="width: 250px;">Obtained Marks</th>
                        </tr>
                    </thead>
                    <tbody id="subjects-tbody">
                        <!-- Dynamic rows populated via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Calculations Summary block -->
            <h6 class="text-secondary fw-bold uppercase mb-3"><i class="fa-solid fa-square-poll-vertical me-2"></i>Calculated Summary Outcome</h6>
            <div class="card-box bg-light border-0 shadow-none mb-4">
                <div class="row g-3 text-center align-items-center fw-semibold">
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Total Marks</div>
                        <div class="fs-4 text-dark" id="summary-total">0</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Total Obtained</div>
                        <div class="fs-4 text-primary" id="summary-obtained">0</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="text-muted small">Percentage</div>
                        <div class="fs-4 text-info" id="summary-percentage">0.00%</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="text-muted small">Assigned Grade</div>
                        <div class="fs-4 text-warning" id="summary-grade">-</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="text-muted small">Academic Result</div>
                        <div id="summary-result">-</div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fa-solid fa-check me-2"></i>Save Marksheet</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classSelect = document.getElementById('class_id');
        const examSelect = document.getElementById('exam_id');
        const studentSelect = document.getElementById('student_id');
        
        const marksEntrySection = document.getElementById('marks-entry-section');
        const subjectsTbody = document.getElementById('subjects-tbody');

        const summaryTotal = document.getElementById('summary-total');
        const summaryObtained = document.getElementById('summary-obtained');
        const summaryPercentage = document.getElementById('summary-percentage');
        const summaryGrade = document.getElementById('summary-grade');
        const summaryResult = document.getElementById('summary-result');
        const sessionInput = document.getElementById('academic_session');

        // 1. Class selection triggers exam & student loading
        classSelect.addEventListener('change', function () {
            const classId = classSelect.value;
            marksEntrySection.classList.add('d-none');
            
            if (!classId) {
                resetDropdown(studentSelect, 'Choose class first...');
                resetDropdown(examSelect, 'Select exam...');
                return;
            }

            // Filter Exams for this Class on frontend
            let activeExamsCount = 0;
            document.querySelectorAll('.exam-option').forEach(option => {
                if (option.getAttribute('data-class-id') == classId) {
                    option.classList.remove('d-none');
                    activeExamsCount++;
                } else {
                    option.classList.add('d-none');
                }
            });

            if (activeExamsCount === 0) {
                examSelect.value = '';
                alert('No exams are scheduled for this class. Please schedule one first.');
            }

            // Load Students via AJAX (Active only)
            fetch(`{{ url('/marksheets/students') }}/${classId}`)
                .then(res => res.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">Select student...</option>';
                    if (data.length > 0) {
                        data.forEach(student => {
                            studentSelect.innerHTML += `<option value="${student.id}">${student.name} (Roll: ${student.roll_number}) - ${student.admission_number}</option>`;
                        });
                        studentSelect.removeAttribute('disabled');
                    } else {
                        studentSelect.innerHTML = '<option value="">No active students in this class</option>';
                        studentSelect.setAttribute('disabled', 'disabled');
                    }
                });
        });

        // 2. Student & Exam selection triggers subjects/marks grid loading
        function checkAndLoadSubjects() {
            const classId = classSelect.value;
            const studentId = studentSelect.value;
            const examId = examSelect.value;

            if (classId && studentId && examId) {
                // Fetch Subjects
                fetch(`{{ url('/marksheets/subjects') }}/${classId}`)
                    .then(res => res.json())
                    .then(data => {
                        subjectsTbody.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(sub => {
                                subjectsTbody.innerHTML += `
                                    <tr>
                                        <td class="text-start fw-semibold">${sub.name}</td>
                                        <td class="sub-total-marks" data-val="${sub.total_marks}">${sub.total_marks}</td>
                                        <td class="sub-passing-marks" data-val="${sub.passing_marks}">${sub.passing_marks}</td>
                                        <td>
                                            <input type="number" name="marks[${sub.id}]" class="form-control text-center mx-auto obtained-marks-input border-primary" style="width: 150px;" min="0" max="${sub.total_marks}" data-passing="${sub.passing_marks}" data-total="${sub.total_marks}" value="0" required>
                                            <div class="invalid-feedback text-danger small mt-1">Must be between 0 and ${sub.total_marks}</div>
                                        </td>
                                    </tr>
                                `;
                            });
                            
                            // Attach dynamic typing events to marks inputs
                            document.querySelectorAll('.obtained-marks-input').forEach(input => {
                                input.addEventListener('input', calculateGrades);
                            });

                            marksEntrySection.classList.remove('d-none');
                            calculateGrades();
                        } else {
                            marksEntrySection.classList.add('d-none');
                            alert('No active subjects configured for this class. Add subjects first.');
                        }
                    });
            } else {
                marksEntrySection.classList.add('d-none');
            }
        }

        studentSelect.addEventListener('change', checkAndLoadSubjects);
        examSelect.addEventListener('change', function() {
            const selectedOption = examSelect.options[examSelect.selectedIndex];
            if (selectedOption) {
                // Read academic session from selected exam text
                const text = selectedOption.textContent;
                const match = text.match(/\((.*?)\)/);
                if (match && match[1]) {
                    sessionInput.value = match[1];
                }
            }
            checkAndLoadSubjects();
        });

        // 3. Grade Calculator (Realtime while typing)
        function calculateGrades() {
            let totalMax = 0;
            let totalObtained = 0;
            let failedAnySubject = false;
            let validationFailed = false;

            document.querySelectorAll('.obtained-marks-input').forEach(input => {
                const obtainedVal = parseFloat(input.value) || 0;
                const maxVal = parseFloat(input.getAttribute('data-total'));
                const passingVal = parseFloat(input.getAttribute('data-passing'));

                totalMax += maxVal;

                if (obtainedVal < 0 || obtainedVal > maxVal) {
                    input.classList.add('is-invalid');
                    validationFailed = true;
                } else {
                    input.classList.remove('is-invalid');
                    totalObtained += obtainedVal;
                }

                if (obtainedVal < passingVal) {
                    failedAnySubject = true;
                }
            });

            if (validationFailed || totalMax === 0) {
                summaryTotal.textContent = '-';
                summaryObtained.textContent = '-';
                summaryPercentage.textContent = '-';
                summaryGrade.textContent = '-';
                summaryResult.innerHTML = '-';
                return;
            }

            const percentage = (totalObtained / totalMax) * 100;
            const grade = getGradeFromPercentage(percentage);
            const result = failedAnySubject ? 'FAIL' : 'PASS';

            // Update displays
            summaryTotal.textContent = totalMax;
            summaryObtained.textContent = totalObtained;
            summaryPercentage.textContent = percentage.toFixed(2) + '%';
            summaryGrade.textContent = grade;
            
            if (result === 'PASS') {
                summaryResult.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">PASS</span>';
            } else {
                summaryResult.innerHTML = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6">FAIL</span>';
            }
        }

        // Percentage to Grade scale
        function getGradeFromPercentage(p) {
            if (p >= 90) return 'A+';
            if (p >= 80) return 'A';
            if (p >= 70) return 'B';
            if (p >= 60) return 'C';
            if (p >= 50) return 'D';
            if (p >= 40) return 'E';
            return 'F';
        }

        function resetDropdown(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.setAttribute('disabled', 'disabled');
        }
    });
</script>
@endsection
