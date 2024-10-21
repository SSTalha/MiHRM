<?php

namespace App\Services\Admin;
use App\Constants\Messages;
use App\Models\Project;
use App\Models\Employee;
use App\Helpers\Helpers;
use App\Models\ProjectAssignment;
use App\DTOs\ProjectDTOs\ProjectCreateDTO;
use App\DTOs\ProjectDTOs\ProjectAssignmentDTO;
use Symfony\Component\HttpFoundation\Response;

class ProjectService
{

    /**
     * Summary of createProject
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createProject($request)
    {
        try {
            $dto = new ProjectCreateDTO($request);
            $project = Project::create($dto->toArray());

            return Helpers::result("Project created successfully.", Response::HTTP_CREATED, $project);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of updateProject
     * @param mixed $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProject($request,$id){
        try {
            $project = Project::findOrFail($id);
            $project->update($request->only(['title', 'description']));
            return Helpers::result("Project Updated Successfully",Response::HTTP_CREATED,$project);
        
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of deleteProject
     * @param mixed $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteProject($request,$id){
        try {
             $project=Project::findorFail($id);
             $project->delete();
             return Helpers::result("Project deleted successfully",Response::HTTP_OK);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of assignProject
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignProject($request)
    {
        try {
            $dto = new ProjectAssignmentDTO($request);
    
            foreach ($dto->employee_ids as $employee_id) {
                $employee = Employee::find($employee_id);
    
                if (!$employee) {
                    continue;
                }
                if ($employee->user->hasRole('hr')) {
                    return Helpers::result("Can't assign project. The user is an HR.", Response::HTTP_BAD_REQUEST);
                }
                ProjectAssignment::create([
                    'employee_id' => $employee_id,
                    'project_id' => $dto->project_id
                ]);
            }
    
            return Helpers::result("Project assigned successfully to all employees.", Response::HTTP_CREATED);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    /**
     * Summary of getAllAssignedProjects
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllAssignedProjects($request)
    {
        try {
            $assignedProjects = ProjectAssignment::with(['project', 'employee.user'])->get();

            if ($assignedProjects->isEmpty()) {
                return Helpers::result("No projects assigned yet.", Response::HTTP_NOT_FOUND);
            }

            $data = $assignedProjects->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'project_id' => $assignment->project_id,
                    'employee_id' => $assignment->employee_id,
                    'status' => $assignment->status,
                    'title' => $assignment->project->title,
                    'deadline' => $assignment->project->deadline,
                    'name' => $assignment->employee->user->name,
                    'email' => $assignment->employee->user->email,
                ];
            });

            return Helpers::result("All assigned projects fetched successfully.", Response::HTTP_OK, $data);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of getAllProjects
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllProjects($request)
    {
        try{
            $projects = Project::all();

            if ($projects->isEmpty()) {
                return Helpers::result("No projects available.", Response::HTTP_NOT_FOUND);
            }
            return Helpers::result("All projects fetched successfully.", Response::HTTP_OK, $projects);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Summary of projectCount
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function projectCount($request) {
        try {
            $pendingProject = ProjectAssignment::where('status', 'pending')->count();
            $inProgressProject = ProjectAssignment::where('status', 'in_progress')->count();
            $completedProject = ProjectAssignment::where('status', 'completed')->count();

            $data = [
                'pendingProject' => $pendingProject,
                'inProgressProject' => $inProgressProject,
                'completedProject' => $completedProject
            ];
            
            return Helpers::result('Projects count', Response::HTTP_OK, $data);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}