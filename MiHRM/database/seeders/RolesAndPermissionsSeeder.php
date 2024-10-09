<?php

namespace Database\Seeders;

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
            //admin specific
            'User can see all users',
            'User can manage  leaves (accept/reject)',
            'User can manage all users department and position (update)',
            'User can add users (employee,hr)',
            'User can manage payroll',
            'User can see Department Details',
            'User can see all users Working Hours',
            'User can create Projects',

            //hr specific
            'User can see all employee',
            'User can manage employee leaves (accept/reject)',
            'User can manage employee department and position (update)',
            'Users can see its and employee Working Hours',
            'User can assign Projects to employees',

            // Admin-hr common Permissions
            'User can view employee projects status',
            'User can see Attendance Record of all users',
            'User can view attendance records',

            // employee specific Permissions
            'User can manage  ongoing/completed projects',
            'User can see Assigned Projects',
            'User can see its Working Hours',
            'User can see Attendance Record of itself',

            //employee and hr common
            'User can Check-in/Check-out',
            'User can submit Leave Applications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'User can see all users',
            'User can manage  leaves (accept/reject)',
            'User can manage all users department and position (update)',
            'User can add users (employee,hr)',
            'User can manage payroll',
            'User can see Department Details',
            'User can see all users Working Hours',
            'User can view employee projects status',
            'User can see Attendance Record of all users',
            'User can create Projects',
            'User can view attendance records',

        ]);

        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $hrRole->syncPermissions([
            'User can see all employee',
            'User can manage employee leaves (accept/reject)',
            'User can manage employee department and position (update)',
            'Users can see its and employee Working Hours',
            'User can view employee projects status',
            'User can Check-in/Check-out',
            'User can submit Leave Applications',
            'User can see Attendance Record of all users',
            'User can assign Projects to employees',
            'User can view attendance records',
        ]);

        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'User can Check-in/Check-out',
            'User can submit Leave Applications',
            'User can see Attendance Record of itself',
            'User can manage  ongoing/completed projects',
            'User can see Assigned Projects',
            'User can see its Working Hours',
        ]);
    }
}
