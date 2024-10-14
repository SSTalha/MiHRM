<?php

namespace App\Services\Admin;

use App\Jobs\QueueJobs\LeaveStatus\AcceptLeaveRequestJob;
use App\Jobs\QueueJobs\LeaveStatus\RejectLeaveRequestJob;
use App\Models\User;
use App\Models\Project;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;
use App\DTOs\ProjectDTOs\ProjectCreateDTO;
use App\DTOs\EmployeeDTOs\EmployeeUpdateDTO;
use App\DTOs\ProjectDTOs\ProjectAssignmentDTO;
use Symfony\Component\HttpFoundation\Response;

class AdminService
{

    private function isSelfApproval($authUser, $requestingUser){
    return $authUser->id === $requestingUser->id;
    }

    private function isAuthorizedToHandle($authUser, $requestingUser){
    return !(
        ($requestingUser->hasRole('hr') && !$authUser->hasRole('admin')) ||
        ($requestingUser->hasRole('employee') && !$authUser->hasAnyRole(['admin', 'hr']))
    );
    }

    private function handleStatusActions($status, $leaveRequest, $requestingUser){
    if ($status === 'approved') {
        $this->updateAttendanceStatus($leaveRequest->employee_id, 'onleave');
        AcceptLeaveRequestJob::dispatch($requestingUser);
        return Helpers::result('Leave request has been approved', Response::HTTP_OK);
    
    } elseif ($status === 'rejected') {
        RejectLeaveRequestJob::dispatch($requestingUser);
        return Helpers::result('Leave request has been rejected', Response::HTTP_OK);
    }

    return Helpers::result('Invalid leave request status', Response::HTTP_BAD_REQUEST);
    }

    /**
     * Summary of handleLeaveRequest
     * @param mixed $leaveRequestId
     * @param mixed $status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function handleLeaveRequest($leaveRequestId, $status){
    try {
        // dd($status);
        $authUser = Auth::user();
        $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);
        $requestingEmployee = $leaveRequest->employee;

        if (!$requestingEmployee || !$requestingEmployee->user) {
            return Helpers::result('Employee record or associated user not found', Response::HTTP_NOT_FOUND);
        }

        $requestingUser = $requestingEmployee->user;

        if ($this->isSelfApproval($authUser, $requestingUser)) {
            return Helpers::result('You cannot approve/reject your own leave request.', Response::HTTP_FORBIDDEN);
        }

        if (!$this->isAuthorizedToHandle($authUser, $requestingUser)) {
            return Helpers::result('Unauthorized action.', Response::HTTP_FORBIDDEN);
        }

        $leaveRequest->update(['status' => $status]);

        return $this->handleStatusActions($status, $leaveRequest, $requestingUser);
        
    } catch (\Exception $e) {
        return Helpers::result("An error occurred while handling the leave request: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    }

    /**
     * Fetch employees by department ID
     *
     * @param int $department_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeesByDepartment($department_id)
    {
        try{
            $employees = Employee::where('department_id', $department_id)
            ->with(['user:id,name,email', 'department:id,name'])
            ->get(['id', 'user_id', 'position', 'date_of_joining', 'department_id']);

            if ($employees->isEmpty()) {
                return Helpers::result("No employees found for this department", Response::HTTP_NOT_FOUND);
            }

            $formattedData = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->user->name,
                    'email' => $employee->user->email,
                    'position' => $employee->position,
                    'date_of_joining' => $employee->date_of_joining,
                    'department_name' => $employee->department->name
                ];
            });

            return Helpers::result("Employees fetched successfully", Response::HTTP_OK, [
                'department_id' => $department_id,
                'employees' => $formattedData
            ]);

        }catch(\Exception $e){
            return Helpers::result("An error occurred while fetching employees: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a user and their related employee record
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserAndEmployee($user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user) {
                return Helpers::result("User not found", Response::HTTP_NOT_FOUND);
            }

            Employee::where('user_id', $user_id)->delete();
            $user->delete();

            return Helpers::result("User and employee deleted successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while deleting the user and employee: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    /**
     * Summary of updateEmployee
     * @param mixed $data
     * @param mixed $employee_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateEmployee($data, $employee_id)
    {
        try {
            $employee = Employee::find($employee_id);

            if (!$employee) {
                return Helpers::result("Employee not found", Response::HTTP_NOT_FOUND);
            }
            $dto = new EmployeeUpdateDTO($data);
            $employee->update($dto->toArray());

            return Helpers::result("Employee updated successfully", Response::HTTP_OK, $employee);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while updating the employee: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Summary of getAllDepartments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllDepartments()
    {
        try {
            $departments = Department::all();
            return Helpers::result('Departments retrieved successfully', Response::HTTP_OK, $departments);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while fetching departments: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }


    /**
     * Summary of updateAttendanceStatus
     * @param mixed $employeeId
     * @param mixed $status
     * @return void
     */
    protected function updateAttendanceStatus($employeeId, $status)
    {
        try {
            $today = now()->toDateString();
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('created_at', $today)
                ->first();
    
            if ($attendance) {
                $attendance->update(['status' => $status]);
            } else {
                return Helpers::result("Attendance record not found for today.", Response::HTTP_NOT_FOUND);
            }
    
            return Helpers::result("Attendance status updated successfully.", Response::HTTP_OK);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while updating attendance: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of createProject
     * @param mixed $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createProject($data)
    {
        try {
            $dto = new ProjectCreateDTO($data);
            $project = Project::create($dto->toArray());

            return Helpers::result("Project created successfully.", Response::HTTP_CREATED, $project);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while creating the project: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of updateProject
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProject($request,$id){
        try {
             $project = Project::findOrFail($id);
        $project->update($request->only(['title', 'description']));
        return Helpers::result("Project Updated Successfully",Response::HTTP_CREATED,$project);
        } catch (\Exception $e) {
            return Helpers::result("Failed to update project".$e->getMessage(),Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * Summary of deleteProject
     * @return mixed|\Illuminate\Http\JsonResponse
     *

     */
    public function deleteProject($id){
        try {
             $project=Project::findorFail($id);
             $project->delete();
             return Helpers::result("Project deleted successfully",Response::HTTP_OK);
        } catch (\Exception $e) {
           return Helpers::result("Project deleted failed",Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * Summary of assignProject
     * @param mixed $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignProject($data)
    {
        try {
            $dto = new ProjectAssignmentDTO($data);
    
            foreach ($dto->employee_ids as $employee_id) {
                $employee = Employee::find($employee_id);
    
                if (!$employee) {
                    continue;
                }
                if ($employee->user->hasRole('hr')) {
                    return Helpers::result("Can't assign project. The user is an HR.", Response::HTTP_BAD_REQUEST);
                }
                $assignment = ProjectAssignment::create([
                    'employee_id' => $employee_id,
                    'project_id' => $dto->project_id
                ]);
            }
    
            return Helpers::result("Project assigned successfully to all employees.", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while assigning the project: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    /**
     * Summary of getAllAssignedProjects
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllAssignedProjects()
    {
        try {
            $assignedProjects = ProjectAssignment::with(['project', 'employee.user'])->get();

            if ($assignedProjects->isEmpty()) {
                return Helpers::result("No projects assigned yet.", Response::HTTP_NOT_FOUND);
            }

            return Helpers::result("All assigned projects fetched successfully.", Response::HTTP_OK, $assignedProjects);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while fetching assigned projects: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllProjects()
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            return Helpers::result("No projects available.", Response::HTTP_NOT_FOUND);
        }
        return Helpers::result("All projects fetched successfully.", Response::HTTP_OK, $projects);
    }

    /*
    * Summary of addDepartment
    * @return mixed
    */
    public function addDepartment($request){
        try {
            $department= Department::create([
        'name' => $request->get('name'),
        ]);

        return Helpers::result("Department added successfully.", Response::HTTP_OK, $department);
        } catch (\Exception $e) {
           return Helpers::result("Department added failed.".$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAllEmployees()
    {
        try {
            
            $employees = Employee::with(['user.roles'])->get();
            if ($employees->isEmpty()) {
                return Helpers::result("No employees found.", Response::HTTP_NOT_FOUND);
            }

            $data = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'user_id' => $employee->user_id,
                    'position' => $employee->position,
                    'department_id' => $employee->department_id,
                    'pay' => $employee->pay,
                    'date_of_joining' => $employee->date_of_joining,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                    'user_id' => $employee->user->id,
                    'role' => $employee->user->roles->first()->name ?? 'N/A', // Fetch the first role (if available)
                ];
            });

            return Helpers::result("All employees fetched successfully.", Response::HTTP_OK, $data);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while fetching employees: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEmployeeRoleCounts()
    {
        try {
            $hrCount = Employee::whereHas('user.roles', function($query) {
                $query->where('name', 'hr');
            })->count();

            $employeeCount = Employee::whereHas('user.roles', function($query) {
                $query->where('name', 'employee');
            })->count();

            $data = [
                'hr_count' => $hrCount,
                'employee_count' => $employeeCount
            ];

            return Helpers::result("Employee role counts fetched successfully.", Response::HTTP_OK, $data);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred while fetching employee role counts: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
