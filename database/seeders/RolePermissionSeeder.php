<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'manage-students' => 'Students Management',
            'manage-admissions' => 'Admissions Management',
            'manage-classes' => 'Class Management',
            'manage-subjects' => 'Subject Management',
            'manage-exams' => 'Exam Management',
            'manage-fee-settings' => 'Fee Settings Access',
            'manage-fee-collection' => 'Collect Student Fees',
            'manage-receipts' => 'Manage Receipts',
            'manage-arrears' => 'Manage Arrears',
            'manage-marks' => 'Enter/Edit Marks',
            'manage-marksheets' => 'Generate Marksheets',
            'view-reports' => 'View System Reports',
            'manage-users' => 'Manage Users',
            'manage-settings' => 'Manage School Settings',
        ];

        $permissionModels = [];
        foreach ($permissions as $slug => $name) {
            $permissionModels[$slug] = Permission::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // 2. Create Roles
        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Full access to the system.']
        );

        $admin = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Academic administration access.']
        );

        $accountant = Role::updateOrCreate(
            ['slug' => 'accountant'],
            ['name' => 'Accountant', 'description' => 'Financial and collection access.']
        );

        $teacher = Role::updateOrCreate(
            ['slug' => 'teacher'],
            ['name' => 'Teacher', 'description' => 'Marks entry and academic reporting.']
        );

        // 3. Assign Permissions
        // Super Admin gets all
        $superAdmin->permissions()->sync(array_column($permissionModels, 'id'));

        // Admin permissions: Students, Admissions, Classes, Subjects, Reports
        $adminPermissions = [
            $permissionModels['manage-students']->id,
            $permissionModels['manage-admissions']->id,
            $permissionModels['manage-classes']->id,
            $permissionModels['manage-subjects']->id,
            $permissionModels['view-reports']->id,
        ];
        $admin->permissions()->sync($adminPermissions);

        // Accountant permissions: Fee Settings, Fee Collection, Receipts, Arrears, Fee Reports
        $accountantPermissions = [
            $permissionModels['manage-fee-settings']->id,
            $permissionModels['manage-fee-collection']->id,
            $permissionModels['manage-receipts']->id,
            $permissionModels['manage-arrears']->id,
            $permissionModels['view-reports']->id, // Fee Reports
        ];
        $accountant->permissions()->sync($accountantPermissions);

        // Teacher permissions: Students, Subjects, Exams, Marks, Marksheets
        $teacherPermissions = [
            $permissionModels['manage-students']->id,
            $permissionModels['manage-subjects']->id,
            $permissionModels['manage-exams']->id,
            $permissionModels['manage-marks']->id,
            $permissionModels['manage-marksheets']->id,
        ];
        $teacher->permissions()->sync($teacherPermissions);
    }
}
