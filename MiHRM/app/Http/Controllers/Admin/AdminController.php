<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Http\Requests\Admin\AssignProjectRequest;
use App\Http\Requests\Admin\CreateProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
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
    public function updateProject(UpdateProjectRequest $request,$id): JsonResponse
    {
        return $this->adminService->updateProject($request,$id);
    }
    public function deleteProject($id): JsonResponse
    {
        return $this->adminService->deleteProject($id);
    }

    public function assignProject(AssignProjectRequest $request): JsonResponse
    {
        $data = $request->all();
        return $this->adminService->assignProject($data);
    }

    public function getAllAssignedProjects(): JsonResponse
    {
        return $this->adminService->getAllAssignedProjects();
    }
    public function getAllProjects()
    {
        return $this->adminService->getAllProjects();
    }
    public function addDepartment(DepartmentRequest $request){
        return $this->adminService->addDepartment($request);
    }
}
