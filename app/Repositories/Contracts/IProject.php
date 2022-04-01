<?php

namespace App\Repositories\Contracts;

interface IProject
{
    public function addComment($projectId, array $data);
    public function like($id);
    public function isLikedByUser($projectId);
}
