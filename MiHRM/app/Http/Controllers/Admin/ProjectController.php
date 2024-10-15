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
        
        return $this->projectService->createProject($request->validated());
    }
    public function updateProject(UpdateProjectRequest $request,$id): JsonResponse
    {
        return $this->projectService->updateProject($request,$id);
    }
    public function deleteProject($id): JsonResponse
    {
        return $this->projectService->deleteProject($id);
    }

    public function assignProject(AssignProjectRequest $request): JsonResponse
    {
        $data = $request->all();
        return $this->projectService->assignProject($data);
    }

    public function getAllAssignedProjects(): JsonResponse
    {
        return $this->projectService->getAllAssignedProjects();
    }
    public function getAllProjects()
    {
        return $this->projectService->getAllProjects();
    }
}
