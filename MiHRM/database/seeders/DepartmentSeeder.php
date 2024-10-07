<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department; // Make sure the Department model is imported

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create two departments: IT and HR
        Department::firstOrCreate(['name' => 'IT']);
        Department::firstOrCreate(['name' => 'HR']);
    }
}
