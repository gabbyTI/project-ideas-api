<?php

namespace App\Repositories\Contracts;

interface IProject
{
    public function addComment($projectId, array $data);
}
