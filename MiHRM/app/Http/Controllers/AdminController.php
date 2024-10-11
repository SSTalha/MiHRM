<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssignProjectRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Get employees by department ID
     *
     * @param int $department_id
     * @return JsonResponse
     */
    public function getEmployeesByDepartment($department_id): JsonResponse
    {
        // Call the AdminService to fetch the employees and return response
        return $this->adminService->getEmployeesByDepartment($department_id);
    }

    /**
     * Delete a user and their related employee record
     *
     * @param int $user_id
     * @return JsonResponse
     */
    public function deleteUser($user_id): JsonResponse
    {
        // Call the AdminService to delete the user and employee
        return $this->adminService->deleteUserAndEmployee($user_id);
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $employee_id): JsonResponse
    {
       
        return $this->adminService->updateEmployee($request->validated(), $employee_id);
    }

    public function getAllDepartments(){
        return $this->adminService->getAllDepartments();
    }

    public function handleLeaveRequest($leaveRequestId, $status)
    {
        
        return $this->adminService->handleLeaveRequest($leaveRequestId, $status);
    }

    public function createProject(CreateProjectRequest $request): JsonResponse
    {
        
        return $this->adminService->createProject($request->validated());
    }

    public function assignProject(AssignProjectRequest $request): JsonResponse
    {
        // Pass the validated data to the service
        return $this->adminService->assignProject($request->validated());
    }

    public function getAllAssignedProjects(): JsonResponse
    {
        // Delegate the task to AdminService
        return $this->adminService->getAllAssignedProjects();
    }
}
