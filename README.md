# Super Dawn School Lakhi - School Management System

A production-ready Laravel 10 application designed for **SUPER DAWN SCHOOL LAKHI** to manage admissions, student records, fee collection with ledger transaction logs, receipts, academic examination marksheets, and role-based staff authorization.

---

## 🔑 Default Login Credentials

Use the following credentials to log in. The default password for all demo accounts is `password`:

| Role | Email Address | Permissions Scope |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@superdawn.com` | Complete system access |
| **Admin** | `admin@superdawn.com` | Admissions, Student Profiles, Classes, Subjects, Reports |
| **Accountant** | `accountant@superdawn.com` | Fee Collections, Receipts, Arrears Ledger, Fee Reports |
| **Teacher** | `teacher@superdawn.com` | Subjects Directory, Exam Schedules, Mark Entries, Marksheets |

---

## 🛠️ Installation Instructions

Follow these steps to deploy the application on your system:

### 1. Set Workspace & Copy Vendor dependencies
We recommend opening this directory as your active IDE workspace:
`C:\Users\dell\.gemini\antigravity-ide\scratch\super-dawn-school`

Since terminal execution is restricted by the Windows sandbox in this session, please run composer locally or copy the existing `vendor` folder:
- **Option A (Composer CLI):** Run `composer install` in this directory.
- **Option B (Copy folder):** Copy the `vendor` directory from `C:\laragon\www\laravel-website-enhancements-kiohana.com` directly into `C:\Users\dell\.gemini\antigravity-ide\scratch\super-dawn-school\vendor`.

### 2. Configure Local Web Server (Laragon / XAMPP)
Move or symlink this folder to your local Laragon web root (`C:\laragon\www\super-dawn-school`), or point a virtual host to the `public/` directory:
- Virtual Host url: `http://super-dawn-school.test`
- Standard localhost subpath: `http://localhost/super-dawn-school/public/`

### 3. Create the Database
Open phpMyAdmin or your MySQL client and create a new database:
```sql
CREATE DATABASE super_dawn_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Migrations & Database Seeders
You can run migrations and seeds in one of two ways:

#### Method A: Programmatic Web Runner (Recommended)
Open your browser and navigate to the built-in Artisan runner utility:
`http://localhost/super-dawn-school/public/artisan_runner.php` (or `http://super-dawn-school.test/artisan_runner.php`)
Click **"Wipe, Migrate & Seed"** or **"Run Migrations"** followed by **"Run Seeders"** to execute DB operations.

#### Method B: Local CLI Terminal
Open your terminal in this directory and execute:
```bash
php artisan migrate --seed
```

---

## 🧪 Running Automated Feature Tests

To verify all calculations (percentage formula, grade boundaries, pass/fail validation, ledger tracking, unique receipt checks, and role guards), execute the PHPUnit suite:
```bash
php artisan test --filter=SchoolManagementTest
```

---

## 📁 Key Project Components

The modules have been structured cleanly following Laravel MVC patterns:

- **Database Migrations** (`database/migrations/`):
  - `...000001_create_schools_table.php` (Institutions details)
  - `...000002_create_roles_table.php` & `...000003_create_permissions_table.php`
  - `...000004_create_users_table.php` (Links user account to role)
  - `...000005_create_classes_table.php`
  - `...000006_create_students_table.php` (Admission overrides, arrears, no `mother_name` column)
  - `...000007_create_admissions_table.php`
  - `...000008_create_subjects_table.php` & `...000009_create_exams_table.php`
  - `...000010_create_fee_settings_table.php`
  - `...000011_create_fee_receipts_table.php` & `...000012_create_fee_transactions_table.php` (Historical ledger logs)
  - `...000013_create_marksheets_table.php` & `...000014_create_marksheet_subjects_table.php`

- **Services Layer** (`app/Services/`):
  - `FeeService.php`: Backend fee processing, calculations, ledger posting.
  - `ReceiptService.php`: Sequencing unique receipt IDs and PDF render wrappers.
  - `MarksheetService.php`: Aggregate mark sums, grade ranges, pass/fail validations, PDF compile.
  - `ReportService.php`: Database query filters for pass/fail, monthly collection totals, outstanding arrears.

- **Controllers Layer** (`app/Http/Controllers/`):
  - `AuthController.php`: Login/Logout authentication.
  - `DashboardController.php`: Aggregates stats cards and visual chart datasets.
  - `AdmissionController.php`: Manages student admissions and prints form PDFs.
  - `StudentController.php`: Renders rosters (hides inactive students by default), deactivates/reactivates.
  - `ClassController.php` / `SubjectController.php` / `ExamController.php`: CRUD registries.
  - `FeeSettingController.php` / `FeeCollectionController.php`: Financial operations, AJAX student state queries.
  - `ReceiptController.php` / `MarksheetController.php`: Print slip views, marks entry card, class result indices.
  - `ReportController.php`: Configures selection menus and exports tabular reports.
  - `UserController.php`: Staff user directories.
