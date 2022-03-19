<?php

namespace App\Providers;

use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IProject;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IProject::class, ProjectRepository::class);
        $this->app->bind(IComment::class, CommentRepository::class);
    }
}
