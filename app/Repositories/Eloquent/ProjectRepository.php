<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\IProject;

class ProjectRepository extends BaseRepository implements IProject
{
    public function model()
    {
        return Project::class;
    }

    public function addComment($project, array $data){
        $comment = $project->comments()->create($data);
        return $comment;
    }

    public function like($projectId){
        $project = $this->model->findOrFail($projectId);

        if($project->isLikedByUser(auth()->id())){
            $project->unlike();
        }else{
            $project->like();
        }
    }

    public function isLikedByUser($projectId){
        $project = $this->model->findOrFail($projectId);

        return $project->isLikedByUser(auth()->id());
    }

}
