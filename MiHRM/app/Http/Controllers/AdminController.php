<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}
