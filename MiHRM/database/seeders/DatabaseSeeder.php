<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AttendanceSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
           AdminSeeder::class,
           RolesAndPermissionsSeeder::class,
           DepartmentSeeder::class,
           AttendanceSeeder::class,
        ]);
    }
}
