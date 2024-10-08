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
            // Common Permissions
            'User can see all employee',
            'User can manage employee leaves (accept/reject)',
            'User can manage employee attendance',
            'User can manage employee department (add/remove)',
            'User can manage employee check-in/check-out',

            // Admin-Specific Permissions
            'User can add employee',
            'User can track attendance',
            'User can add/remove job position',
            'User can add hr and assign department',
            'User can see all hr',
            'User can see hr check-in/check-out',

            // HR-Specific Permissions
            'User can manage employee payroll',
            'User can manage employee joining date',
            'User can manage employee ongoing/completed projects',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $hrRole->syncPermissions([
            'User can see all employee',
            'User can manage employee leaves (accept/reject)',
            'User can manage employee attendance',
            'User can manage employee department (add/remove)',
            'User can manage employee check-in/check-out',
            'User can manage employee payroll',
            'User can manage employee joining date',
            'User can manage employee ongoing/completed projects',
        ]);

        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
    }
}
