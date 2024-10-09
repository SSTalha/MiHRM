<?php

namespace App\Http\Controllers;

use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\AssignProjectRequest;
use App\Http\Requests\Admin\CreateProjectRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }


    public function getEmployeesByDepartment($department_id): JsonResponse
    {
        return $this->adminService->getEmployeesByDepartment($department_id);
    }

    public function deleteUser($user_id): JsonResponse
    {
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
        return $this->adminService->assignProject($request->validated());
    }

    public function getAllAssignedProjects(): JsonResponse
    {
        return $this->adminService->getAllAssignedProjects();
    }
}
