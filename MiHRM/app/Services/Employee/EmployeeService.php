<?php

namespace App\Services\Employee;
use App\Constants\Messages;
use App\DTOs\AuthDTOs\PasswordDTO;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\DTOs\EmployeeDTOs\LeaveRequestDTO;
use App\Models\ProjectAssignment;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeService
{
    /**
     * Summary of passwordSetup
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function passwordSetup($request){
        try{
            $dto = new PasswordDTO($request);

            $user = User::where('email', $dto->email)->first();

            if (!$user || $user->remember_token !== $dto->token) {
                return Helpers::result(Messages::InvalidCredentials,Response::HTTP_BAD_REQUEST);
            }

            $user->password = Hash::make($dto->password);
            $user->remember_token = null;
            $user->save();

            return Helpers::result(Messages::PasswordSetSuccess, Response::HTTP_OK);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * submitLeaveRequest
     * @param mixed $request
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function submitLeaveRequest($request)
    {
        try{
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();

            if (!$employee) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            $leaveRequestDTO = new LeaveRequestDTO($request, $employee->id);
            $leaveRequest = LeaveRequest::create($leaveRequestDTO->toArray());

            return Helpers::result(Messages::LeaveSubmitSuccess, Response::HTTP_OK, $leaveRequest);

        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of getAssignedProjects
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAssignedProjects($request)
    {
        try{
            $employeeId = Auth::user()->employee->id;
            $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
                                                ->with('project')
                                                ->get();
            if ($assignedProjects->isEmpty()) {
                return Helpers::result(Messages::ProjectAssignmentNull, Response::HTTP_OK);
            }
            return Helpers::result(Messages::AssignedProjectFetched, Response::HTTP_OK, $assignedProjects);
        
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of updateProjectStatus
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProjectStatus($request)
    {
       try{
            $employeeId = Auth::user()->employee->id;

            $projectAssignment = ProjectAssignment::where('project_id', $request['project_id'])
                                ->where('employee_id', $employeeId)
                                ->first();

            if (!$projectAssignment) {
                return Helpers::result(Messages::ProjectAssignmentNull, Response::HTTP_NOT_FOUND);
            }

            $projectAssignment->status = $request['status'];
            $projectAssignment->save();

            return Helpers::result(Messages::ProjectStatusSuccess, Response::HTTP_OK, $projectAssignment);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
