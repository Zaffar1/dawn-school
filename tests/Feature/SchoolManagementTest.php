<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Admission;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\FeeReceipt;
use App\Models\FeeTransaction;
use App\Models\Marksheet;
use App\Services\FeeService;
use App\Services\MarksheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SchoolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $accountant;
    protected $teacher;
    protected $class5;
    protected $exam;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Roles and Permissions
        $superAdminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $accountantRole = Role::create(['name' => 'Accountant', 'slug' => 'accountant']);
        $teacherRole = Role::create(['name' => 'Teacher', 'slug' => 'teacher']);

        $manageAdmissions = Permission::create(['name' => 'Admissions', 'slug' => 'manage-admissions']);
        $manageFees = Permission::create(['name' => 'Fees', 'slug' => 'manage-fee-collection']);
        $manageMarks = Permission::create(['name' => 'Marks', 'slug' => 'manage-marksheets']);

        $accountantRole->permissions()->attach($manageFees->id);
        $teacherRole->permissions()->attach($manageMarks->id);

        // 2. Create Users
        $this->superAdmin = User::create([
            'name' => 'Super Admin User',
            'email' => 'super@test.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id
        ]);

        $this->accountant = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@test.com',
            'password' => Hash::make('password'),
            'role_id' => $accountantRole->id
        ]);

        $this->teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password'),
            'role_id' => $teacherRole->id
        ]);

        // 3. Setup Class, Subjects and Exams
        $this->class5 = SchoolClass::create(['name' => 'Class 5', 'status' => 'active']);
        
        Subject::create(['name' => 'Mathematics', 'class_id' => $this->class5->id, 'total_marks' => 100, 'passing_marks' => 40]);
        Subject::create(['name' => 'English', 'class_id' => $this->class5->id, 'total_marks' => 100, 'passing_marks' => 40]);

        $this->exam = Exam::create([
            'name' => 'Annual Examination',
            'class_id' => $this->class5->id,
            'academic_session' => '2026-2027',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-15',
            'status' => 'active'
        ]);
    }

    /**
     * Test Student Creation and Admission workflow.
     */
    public function test_student_admission_workflow(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post(route('admissions.store'), [
            'name' => 'Sajid Ali',
            'father_name' => 'Ali Muhammad',
            'date_of_birth' => '2016-05-10',
            'gender' => 'male',
            'class_id' => $this->class5->id,
            'roll_number' => '501',
            'phone' => '0300-9876543',
            'address' => 'Lakhi Town, Sindh',
            'admission_date' => '2026-03-01',
            'admission_fee' => 3000,
            'monthly_fee' => 2000,
            'exam_fee' => 500,
            'arrears' => 1000,
            'collect_admission_fee' => 0
        ]);

        $response->assertRedirect(route('admissions.index'));

        // Verify Student exists in DB
        $this->assertDatabaseHas('students', [
            'name' => 'Sajid Ali',
            'father_name' => 'Ali Muhammad',
            'roll_number' => '501',
            'arrears' => 1000.00
        ]);

        // Verify Student has no mother_name column
        $student = Student::first();
        $this->assertFalse(isset($student->mother_name), 'The database should NOT contain a mother_name column.');
    }

    /**
     * Test Student Deactivation & Reactivation.
     */
    public function test_student_deactivation_reactivation(): void
    {
        $this->actingAs($this->superAdmin);

        $student = Student::create([
            'admission_number' => 'SD-26001',
            'name' => 'Jamil Ahmed',
            'father_name' => 'Ahmed Ali',
            'date_of_birth' => '2015-08-15',
            'gender' => 'male',
            'class_id' => $this->class5->id,
            'roll_number' => '502',
            'address' => 'Lakhi Town',
            'admission_date' => '2026-03-01',
            'arrears' => 0.00,
            'status' => 'active'
        ]);

        // Deactivate Student
        $response = $this->post(route('students.deactivate', $student->id));
        $response->assertRedirect(route('students.show', $student->id));
        $this->assertEquals('inactive', $student->fresh()->status);

        // Reactivate Student
        $response2 = $this->post(route('students.reactivate', $student->id));
        $response2->assertRedirect(route('students.show', $student->id));
        $this->assertEquals('active', $student->fresh()->status);
    }

    /**
     * Verify that student records cannot be deleted.
     */
    public function test_verify_student_cannot_be_deleted(): void
    {
        $this->actingAs($this->superAdmin);

        $student = Student::create([
            'admission_number' => 'SD-26002',
            'name' => 'Imran Khan',
            'father_name' => 'Mir Khan',
            'date_of_birth' => '2015-02-12',
            'gender' => 'male',
            'class_id' => $this->class5->id,
            'roll_number' => '503',
            'address' => 'Lakhi',
            'admission_date' => '2026-03-01',
            'status' => 'active'
        ]);

        // Trying to access DELETE route directly should result in a 404 or method not allowed,
        // because we haven't registered any DELETE route.
        $response = $this->delete("/students/{$student->id}");
        $response->assertStatus(405); // Method Not Allowed (Route is not registered)
        
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    /**
     * Test Fee collection service and arrears ledger.
     */
    public function test_fee_collection_and_arrears(): void
    {
        $student = Student::create([
            'admission_number' => 'SD-26003',
            'name' => 'Waseem Shah',
            'father_name' => 'Shah Ali',
            'date_of_birth' => '2015-03-25',
            'gender' => 'male',
            'class_id' => $this->class5->id,
            'roll_number' => '504',
            'address' => 'Lakhi',
            'admission_date' => '2026-03-01',
            'arrears' => 1000.00, // Initial arrears
            'status' => 'active'
        ]);

        $feeService = app(FeeService::class);

        // Scenario: Previous Arrears = 1000, Monthly Fee = 2000, Exam Fee = 500
        // Total Due = 3500. Paid Amount = 2500. Remaining Arrears = 1000.
        $receipt = $feeService->collectFee([
            'student_id' => $student->id,
            'date' => '2026-03-05',
            'admission_fee' => 0.00,
            'monthly_fee' => 2000.00,
            'exam_fee' => 500.00,
            'paid_amount' => 2500.00
        ]);

        $this->assertEquals(3500.00, $receipt->total_amount);
        $this->assertEquals(1000.00, $receipt->remaining_arrears);

        // Verify latest student arrears updated to 1000.00
        $this->assertEquals(1000.00, $student->fresh()->arrears);

        // Verify ledger transactions created
        $this->assertDatabaseHas('fee_transactions', [
            'student_id' => $student->id,
            'receipt_number' => $receipt->receipt_number,
            'previous_arrears' => 1000.00,
            'paid_amount' => 2500.00,
            'remaining_arrears' => 1000.00
        ]);
    }

    /**
     * Test Academic scorecard, percentage, and grade calculations.
     */
    public function test_marksheet_grade_result_calculation(): void
    {
        $student = Student::create([
            'admission_number' => 'SD-26004',
            'name' => 'Kamil Shah',
            'father_name' => 'Shah Muhammad',
            'date_of_birth' => '2016-09-12',
            'gender' => 'male',
            'class_id' => $this->class5->id,
            'roll_number' => '505',
            'address' => 'Lakhi',
            'admission_date' => '2026-03-01',
            'status' => 'active'
        ]);

        $math = Subject::where('name', 'Mathematics')->first();
        $english = Subject::where('name', 'English')->first();

        $marksheetService = app(MarksheetService::class);

        // Enter Marks: Math = 75/100 (Pass), English = 85/100 (Pass)
        // Total Obtained = 160/200, Percentage = 80%, Grade = A, Result = PASS
        $marksheet = $marksheetService->saveMarksheet([
            'student_id' => $student->id,
            'exam_id' => $this->exam->id,
            'academic_session' => '2026-2027',
            'marks' => [
                $math->id => 75,
                $english->id => 85
            ]
        ]);

        $this->assertEquals(200, $marksheet->total_marks);
        $this->assertEquals(160, $marksheet->obtained_marks);
        $this->assertEquals(80.00, $marksheet->percentage);
        $this->assertEquals('A', $marksheet->grade);
        $this->assertEquals('PASS', $marksheet->result);

        // Scenario 2: Student fails one subject (English = 35 < 40)
        // Total Obtained = 110/200, Percentage = 55%, Result = FAIL (even though percentage is 55% which is Grade D)
        $marksheet2 = $marksheetService->saveMarksheet([
            'student_id' => $student->id,
            'exam_id' => $this->exam->id,
            'academic_session' => '2026-2027',
            'marks' => [
                $math->id => 75,
                $english->id => 35
            ]
        ]);

        $this->assertEquals('FAIL', $marksheet2->result);
    }

    /**
     * Test role-based authorization gates.
     */
    public function test_role_authorization_guards(): void
    {
        // 1. Accountant cannot enter marks
        $this->actingAs($this->accountant);
        $response1 = $this->get(route('marksheets.create'));
        $response1->assertStatus(403); // Forbidden

        // 2. Teacher cannot collect fees
        $this->actingAs($this->teacher);
        $response2 = $this->get(route('fee-collection.create'));
        $response2->assertStatus(403); // Forbidden

        // 3. Accountant can collect fees
        $this->actingAs($this->accountant);
        $response3 = $this->get(route('fee-collection.create'));
        $response3->assertStatus(200); // Authorized
    }
}
