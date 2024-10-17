<?php

namespace App\Services\Admin;
use App\Models\Project;
use App\Models\Employee;
use App\Helpers\Helpers;
use App\Models\ProjectAssignment;
use App\DTOs\ProjectDTOs\ProjectCreateDTO;
use App\DTOs\ProjectDTOs\ProjectAssignmentDTO;
use Symfony\Component\HttpFoundation\Response;

class ProjectService
{
    // Add your service methods here
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


}