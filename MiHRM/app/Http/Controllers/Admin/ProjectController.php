<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\AssignProjectRequest;
use App\Http\Requests\Admin\CreateProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Services\Admin\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function createProject(CreateProjectRequest $request): JsonResponse
    {
        
        return $this->projectService->createProject($request);
    }
    public function updateProject(UpdateProjectRequest $request,$id): JsonResponse
    {
        return $this->projectService->updateProject($request,$id);
    }
    public function deleteProject(Request $request,$id): JsonResponse
    {
        return $this->projectService->deleteProject($request,$id);
    }

    public function assignProject(AssignProjectRequest $request): JsonResponse
    {
        $request = $request->all();
        return $this->projectService->assignProject($request);
    }

    public function getAllAssignedProjects(Request $request): JsonResponse
    {
        return $this->projectService->getAllAssignedProjects($request);
    }
    public function getAllProjects(Request $request)
    {
        return $this->projectService->getAllProjects($request);
    }
    public function getProjectCount(Request $request){
        return $this->projectService->projectCount($request);
    }
}
