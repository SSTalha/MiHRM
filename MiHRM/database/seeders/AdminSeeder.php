<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where("name","admin")->first();

        if(!$adminRole) {
            $this->call(RolesAndPermissionsSeeder::class);
            $adminRole = Role::where("name","admin")->first();
        }

        $adminuser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password@123'),
            ]
        );  
        $adminuser->assignRole('admin');
    }
}
