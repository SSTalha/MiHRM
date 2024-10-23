<?php

namespace App\Constants;

class Messages{

    const ExceptionMessage = "An exception occured!";
    const InvalidCredentials = 'Invalid credentials';
    const NotAuthorized = "You are not authorized.";
    const NoPermission = 'You donot have the permission to access this route';

    const InvalidType = "Invalid action type provided.";
    const InvalidCode = 'Invalid code please try again';
    const UserNotAuthenticated = 'User not authenticated';
    const UserRegistered = "User, Employee registered successfully";
    const UserLoggedOut = 'User successfully logged out';
    const UserNotFound = 'User/Employee not found!';
    const UserLoggedIn = "2FA verified successfully, user logged in";
    const UserDeleted = "User and employee deleted successfully";
    const UserUpdated = "User updated successfully.";
    const PasswordLinkSend = 'Password Link sent';
    const PasswordSetSuccess = 'Password reset,setup successfull';
    const AttendanceRecordsSuccess = "Attendance records retrieved successfully";
    const NoAttendanceRecord = 'No attendance records found';
    const AttendanceCountSuccess = 'Attendance counts retrieved successfully';
    const LeaveSubmitSuccess = 'Leave request submitted';
    const ProjectAssignmentNull = "No projects assigned to this employee.";
    const AssignedProjectFetched = "Assigned projects fetched successfully.";
    const ProjectStatusSuccess = "Project status updated successfully.";
    const LeaveRequestNull = 'No leave requests found.';
    const GetLeaveRequest = 'Leave requests retrieved successfully';
    const NoSalaryFound = 'No salary record found for the specified employee and month';
    const SalaryRetreived = 'Salary details retrieved successfully';
    const WorkingHoursRetrieved = "Working hours retrieved successfully";
    const AttendanceRecordRetrieved = "Attendance records retrieved successfully";
    const PerkRequestSubmitted = "Perk request submitted successfully.";
    const CannotApproveOwnRequest = 'You cannot approve/reject your own leave request.';
    const PerkRequestStatus = "Perk request {$status} successfully.";
    const NoPerks = "No perks available.";
    const PerksFetched = "Perks fetched successfully.";
    const AnnouncementCreated = "Announcement created successfully.";
    const AnnouncementStatusUpdated = "Announcement status updated successfully.";
    const NoAnnouncements = "No announcements found.";
    const AnnouncementsRetrieved = "Announcements retrieved successfully.";
    const EmployeesFetched = "Employees fetched successfully";
    const EmployeeUpdated = "Employee updated successfully";
    const EmployeesCount = "Employee role counts fetched successfully.";
    const DeptsRetreived = 'Departments retrieved successfully';
    const DeptsAdded = "Department added successfully.";
    const ProjectCreated = "Project created successfully.";
    const ProjectUpdated = "Project Updated Successfully";
    const ProjectDeleted = "Project deleted successfully";
    const CannotAssign = "Can't assign project. The user is an HR.";
    const ProjectAssigned = "Project assigned successfully to all employees.";
    const NoProjects = "No projects yet.";
    const ProjectsRetrieved = "All projects fetched successfully.";
}