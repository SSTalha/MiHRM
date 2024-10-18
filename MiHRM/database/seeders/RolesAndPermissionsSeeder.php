<?php

namespace Database\Seeders;

use App\GlobalVariables\PermissionVariables;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'User can see Working Hours',
            'User can add users (employee,hr)',
            'User can get employees by departments',
            'User can manage all users in departments (delete)',
            'User can manage all users in departments (update)',
            'User can see Department Details',
            'User can create projects',
            'User can update projects',
            'User can delete projects',
            'User can add department',
            'User can assign Projects to employees',
            'User can view employee assigned projects',
            'User can manage leaves (accept/reject)',
            'User can get leave requests',
            'User can see all employee',
            'User can see all employee count',
            'User can get all projects',
            'User can submit Leave Applications',
            'User can Check-in/Check-out',
            'User can get salary invoice',
            'User can see their assigned projects',
            'User can manage update project status',
            'User can see Attendance Records',

            'User can get his attedance record',
            'User can count projects',
            'User can get daily attendance count',
            'User can update their info'
        ];
        
        

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'User can see Working Hours',
            'User can add users (employee,hr)',
            'User can get employees by departments',
            'User can manage all users in departments (delete)',
            'User can manage all users in departments (update)',
            'User can see Department Details',
            'User can delete projects',
            'User can update projects',
            'User can create projects',
            'User can add department',
            'User can view employee assigned projects',
            'User can manage leaves (accept/reject)',
            'User can get leave requests',
            'User can see all employee',
            'User can see all employee count',
            'User can get all projects',
            'User can get salary invoice',
            'User can see Attendance Records',

            'User can count projects',
            'User can get daily attendance count',
            'User can update their info'

        ]);

        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $hrRole->syncPermissions([
            'User can see Working Hours',
            'User can get employees by departments',
            'User can see Department Details',
            'User can update projects',
            'User can assign Projects to employees',
            'User can view employee assigned projects',
            'User can manage leaves (accept/reject)',
            'User can get leave requests',
            'User can see all employee',
            'User can see all employee count',
            'User can get all projects',
            'User can submit Leave Applications',
            'User can Check-in/Check-out',
            'User can get salary invoice',
            'User can see Attendance Records',

            'User can get his attedance record',
            'User can count projects',
            'User can get daily attendance count',
            'User can update their info'
            
        ]);

        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'User can see Working Hours',
            'User can get leave requests',
            'User can submit Leave Applications',
            'User can Check-in/Check-out',
            'User can get salary invoice',
            'User can see their assigned projects',
            'User can manage update project status',
            'User can see Attendance Records',

            'User can get his attedance record',
            'User can update their info'
        ]);
    }
}