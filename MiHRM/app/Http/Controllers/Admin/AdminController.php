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

    
    public function addDepartment(DepartmentRequest $request){
        return $this->adminService->addDepartment($request);
    }
    public function getAllEmployees(): JsonResponse
    {
        return $this->adminService->getAllEmployees();
    }
    public function getEmployeeRoleCounts(): JsonResponse
    {
        return $this->adminService->getEmployeeRoleCounts();
    }
}
