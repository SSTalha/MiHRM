<?php

namespace App\Constants;

class Messages{

    const ExceptionMessage = "An exception occured!";
    const InvalidCredentials = 'Invalid credentials';

    const InvalidType = "Invalid action type provided.";
    const InvalidCode = 'Invalid code please try again';
    const UserNotAuthenticated = 'User not authenticated';
    const UserRegistered = "User, Employee registered successfully";
    const UserLoggedOut = 'User successfully logged out';
    const UserNotFound = 'User/Employee not found!';
    const UserLoggedIn = "2FA verified successfully, user logged in";
    const PasswordLinkSend = 'Password Link sent';
    const PasswordSetSuccess = 'Password reset,setup successfull';
    const AttendanceRecordsSuccess = "Attendance records retrieved successfully";
    const NoAttendanceRecord = 'No attendance records found';
    const AttendanceCountSuccess = 'Attendance counts retrieved successfully';
    const LeaveSubmitSuccess = 'Leave request submitted';
    const ProjectAssignmentNull = "No projects assigned to this employee.";
    const AssignedProjectFetched = "Assigned projects fetched successfully.";
    const ProjectStatusSuccess = "Project status updated successfully.";

}