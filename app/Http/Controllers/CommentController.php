<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Project;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IProject;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $comments;
    protected $projects;

    public function __construct(IComment $comments, IProject $projects){
        $this->comments = $comments;
        $this->projects = $projects;
    }

    public function store(Request $request,Project $project){
        $request->validate([
            'body' => ['required'], 
        ]);

        $comment = $this->projects->addComment($project,[
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return ApiResponder::successResponse("Created comment", new CommentResource($comment), 201);
    }

    public function delete(Request $request, $commentId){
        $comment = $this->comments->find($commentId);

        $this->authorize("delete", $comment);

        $this->comments->delete($comment->id);

        // return success response
        return ApiResponder::successResponse("Deleted comment", null ,204);
    }
}
