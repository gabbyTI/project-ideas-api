<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Repositories\Contracts\IProject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ProjectController extends Controller
{

    protected $projects;

    public function __construct(IProject $projects)
    {
        return $this->projects = $projects;
    }

    public function getProjects(){
        $projects = $this->projects->all();

        return ApiResponder::successResponse("List of projects", ProjectResource::collection($projects));
    }

    public function getProject(Project $project){
        return ApiResponder::successResponse("Successful", new ProjectResource($project));
    }

    public function createProject(Request $request){
        // validate the request
        $request->validate([
            "name" => ["required", "unique:projects,name"],
            "summary" => ["required", "min:15", "max:200"],
            "description" => ["min:250"],
        ]);

        // create the project for user
        $project = $this->projects->create([
            "user_id" => auth()->id(),
            "name" =>$request->name,
            "slug" =>Str::slug($request->name),
            "summary" =>$request->summary,
            "description" =>$request->description,
        ]);

        // return success response
        return ApiResponder::successResponse("Created project idea successfully", new ProjectResource($project),201);
    }

    public function updateProject(Request $request, Project $project){
        $this->authorize("update", $project);
        // validate the request
        $request->validate([
            "name" => ["required", "unique:projects,name,".$project->id],
            "summary" => ["required", "min:15", "max:200"],
            "description" => ["min:250"],
        ]);

        // create the project for user
        $project = $this->projects->update($project->id,[
            "name" =>$request->name,
            "slug" =>Str::slug($request->name),
            "summary" =>$request->summary,
            "description" =>$request->description,
        ]);

        // return success response
        return ApiResponder::successResponse("Created project idea successfully", new ProjectResource($project),201);
    }

    public function deleteProject(Request $request, Project $project){
        $this->authorize("delete", $project);

        $this->projects->delete($project->id);

        // return success response
        return ApiResponder::successResponse("Deleted project", null ,204);
    }
}
