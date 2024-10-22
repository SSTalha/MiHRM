<?php

namespace App\GlobalVariables;

class PermissionVariables
{
    const Auth = 'auth';
    public static array $login = [
        'path' => '/login',
        'prefix' => self::Auth,
    ];

    public static array $logout = [
        'path' => '/logout',
        'prefix' => self::Auth,
    ];

    public static array $passwordSetup = [
        'path' => '/password-setup',
        'prefix' => self::Auth,
    ];

    public static array $passwordReset = [
        'path' => '/password-reset',
        'prefix' => self::Auth,
    ];

    public static array $passwordResetLink = [
        'path' => '/password-reset-link',
        'prefix' => self::Auth,
    ];

    public static array $verifyTwoFactorCode = [
        'path' => '/verify-2fa-code',
        'prefix' => self::Auth,
    ];

    //admin
    public static array $createPerks = [
        'path' => '/perks-create',
        'permission' => 'User can create perks'
    ];
    //all
    public static array $getPerks = [
        'path' => '/get-all-perks',
        'permission' => 'User can get all perks'
    ];

    //admin hr
    public static array $getPerksRequests = [
        'path' => '/get-perks/requests',
        'permission' => 'User can get perk requests'
    ];

    //admin hr
    public static array $handlePerkRequests = [
        'path' => '/perks/request-handle',
        'permission' => 'User can handle perk requests'
    ];
    //hr and emp
    public static array $sendPerkRequests = [
        'path' => '/send-perk/request',
        'permission' => 'User Can send perk requests'
    ];

    public static array $getAttendanceCount = [
        'path' => '/employee-attendance-count',
        'permission' => 'User can get his attedance record'
    ];

    public static array $getProjectCount = [
        'path' => '/project-count',
        'permission' => 'User can count projects'
    ];
    public static array $getDailyAttendanceCount = [
        'path' => '/daily-attendance-count',
        'permission' => 'User can get daily attendance count'
    ];

    // Admin, HR, and Employee common route
    public static array $updateUser = [
        'path' => '/user/update',
        'permission' => 'User can update their info'
    ];

    public static array $createAnnouncement = [
        'path' => '/announcements-create',
        'permission' => 'User can manage Announcement'
    ];

    public static array $updatePublishedStatus = [
        'path' => '/announcements/update-status',
        'permission' => 'User can manage Announcement'
    ];

    public static array $getAnnouncements = [
        'path' => '/get-announcements',
        'permission' => 'User can view Announcement'
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

    public static $getAllAttendance = [
        'path' => '/get-all-attendance',
        'permission' => 'User can see Working Hours'
    ];

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


    public static function getPermissionEndpoints():array {
        return [
            self::$login,
            self::$logout,
            self::$passwordSetup,
            self::$passwordReset,
            self::$passwordResetLink,
            self::$verifyTwoFactorCode,
            self::$createPerks,
            self::$getPerks,
            self::$getPerksRequests,
            self:: $handlePerkRequests,
            self::$sendPerkRequests,
            self::$getAllAttendance,
            self::$getAttendanceCount,
            self::$getProjectCount,
            self::$getDailyAttendanceCount,
            self::$updateUser,
            self::$createAnnouncement,
            self::$updatePublishedStatus,
            self::$getAnnouncements,
            self::$getEmployeeWorkingHours,
            self::$register,
            self::$getEmployeesByDepartment,
            self::$deleteUser,
            self::$updateEmployee,
            self::$getAllDepartments,
            self::$createProject,
            self::$updateProject,
            self::$deleteProject,
            self::$addDepartment,
            self::$assignProject,
            self::$getAllAssignedProjects,
            self::$handleLeaveRequest,
            self::$getLeaveRequest,
            self::$getAllEmployees,
            self::$getEmployeeRoleCounts,
            self::$getAllProjects,
            self::$submitLeaveRequest,
            self::$checkInCheckOut,
            self::$getSalaryDetails,
            self::$getAssignedProjects,
            self::$updateProjectStatus,
            self::$getEmployeesAttendence,
        ];
    }
}
