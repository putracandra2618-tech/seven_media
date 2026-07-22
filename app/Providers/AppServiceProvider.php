<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\TagPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Contracts\TaskServiceInterface::class,
            \App\Services\TaskService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('access-admin', function (User $user) {
            return method_exists($user, 'isAdmin') ? $user->isAdmin() : false;
        });
    }
}
