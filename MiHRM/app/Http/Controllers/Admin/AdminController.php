<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\AssignProjectRequest;
use App\Http\Requests\Admin\CreateProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }


    public function getEmployeesByDepartment(Request $request,$department_id): JsonResponse
    {
        return $this->adminService->getEmployeesByDepartment($request,$department_id);
    }

    public function deleteUser(Request $request,$user_id): JsonResponse
    {
        return $this->adminService->deleteUserAndEmployee($request,$user_id);
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $employee_id): JsonResponse
    {
       
        return $this->adminService->updateEmployee($request, $employee_id);
    }

    public function getAllDepartments(Request $request){
        return $this->adminService->getAllDepartments($request);
    }

    public function handleLeaveRequest(Request $request,$leaveRequestId, $status)
    {
        return $this->adminService->handleLeaveRequest($request,$leaveRequestId, $status);
    }

    public function addDepartment(DepartmentRequest $request){
        return $this->adminService->addDepartment($request);
    }

    public function getAllEmployees(Request $request): JsonResponse
    {
        return $this->adminService->getAllEmployees($request);
    }

    public function getEmployeeRoleCounts(Request $request): JsonResponse
    {
        return $this->adminService->getEmployeeRoleCounts($request);
    }
    
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        return $this->adminService->updateUser($request);
    }
}
