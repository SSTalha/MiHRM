<?php

namespace App\GlobalVariables;

class PermissionVariables
{
    public static array $login = [
        'path' => '/login',
    ];

    public static array $logout = [
        'path' => '/logout',
    ];

    public static array $passwordSetup = [
        'path' => '/password-setup',
    ];

    public static array $passwordReset = [
        'path' => '/password-reset',
    ];

    public static array $passwordResetLink = [
        'path' => '/password-reset-link',
    ];

    // admin employee hr
    public static array $getEmployeeWorkingHours = [
        'path' => '/get-employee/working-hours',
        'permission' => 'User can see Working Hours'
    ];

    // Admin specific routes
    public static array $register = [
        'path' => '/register',
        'permission' => 'User can add users (employee,hr)'
    ];

    //admin and hr
    public static array $getEmployeesByDepartment = [
        'path' => '/get-employees/department/{department_id}',
        'permission' => 'User can get employees by departments'
    ];

    //admin
    public static array $deleteUser = [
        'path' => '/delete-employees/{user_id}',
        'permission' => 'User can manage all users in departments (delete)'
    ];

    //admin
    public static array $updateEmployee = [
        'path' => '/update-employees/update/{employee_id}',
        'permission' => 'User can manage all users in departments (update)'
    ];

    //admin and hr
    public static array $getAllDepartments = [
        'path' => '/get/departments',
        'permission' => 'User can see Department Details'
    ];

    //admin
    public static array $createProject = [
        'path' => '/create-project',
        'permission' => 'User can create projects'
    ];

    //admin hr
    public static array $updateProject = [
        'path' => '/update-project/{id}',
        'permission' => 'User can update projects'
    ];

    //admin
    public static array $deleteProject = [
        'path' => '/delete-project/{id}',
        'permission' => 'User can delete projects'
    ];

    //admin
    public static array $addDepartment = [
        'path' => '/add-department',
        'permission' => 'User can add department'
    ];

    // HR specific routes
    public static array $assignProject = [
        'path' => '/project-assignments',
        'permission' => 'User can assign Projects to employees'
    ];

    // Admin and HR
    public static array $getAllAssignedProjects = [
        'path' => '/get-assigned-projects',
        'permission' => 'User can view employee assigned projects'
    ];
    //admin hr
    public static array $handleLeaveRequest = [
        'path' => '/leave-requests/{leaveRequestId}/{status}',
        'permission' => 'User can manage leaves (accept/reject)'
    ];

    // public static array $getAllSalaries = [
    //     'path' => '/salaries',
    //     'permission' => 'User can manage payroll'
    // ];

    //admin hr employee
    public static array $getLeaveRequest = [
        'path' => '/get-leave-requests',
        'permission' => 'User can get leave requests'
    ];

    // admin hr
    public static array $getAllEmployees = [
        'path' => '/get-all-employees',
        'permission' => 'User can see all employee'
    ];

    //admin hr
    public static array $getEmployeeRoleCounts = [
        'path' => '/employee-role-counts',
        'permission' => 'User can see all employee count'
    ];

    //admin hr
    public static array $getAllProjects = [
        'path' => '/projects-all',
        'permission' => 'User can get all projects'
    ];

    // HR and Employee common routes
    public static array $submitLeaveRequest = [
        'path' => '/submit/leave',
        'permission' => 'User can submit Leave Applications'
    ];

    public static array $checkInCheckOut = [
        'path' => '/attendance/checkin-out',
        'permission' => 'User can Check-in/Check-out'
    ];

    

    //admin hr employee
    public static array $getSalaryDetails = [
        'path' => '/salary-invoice',
        'permission' => 'User can get salary invoice'
    ];

    // Employee specific routes
    public static array $getAssignedProjects = [
        'path' => '/get-employee/assigned-projects',
        'permission' => 'User can see their assigned projects'
    ];

    public static array $updateProjectStatus = [
        'path' => '/projects/update-status',
        'permission' => 'User can manage update project status'
    ];

    // Admin, HR, and Employee common route
    public static array $getEmployeesAttendence = [
        'path' => '/get-employees-attendence',
        'permission' => 'User can see Attendance Records'
    ];
    public static array $updateUser = [
        'path' => '/user/update',
    ];
    
}
