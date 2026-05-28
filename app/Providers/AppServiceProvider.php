<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Pengguna\RoleRepositoryInterface::class,
            \App\Repositories\Pengguna\RoleRepository::class
        );

        $this->app->bind(
            \App\Repositories\RolePermission\PermissionRepositoryInterface::class,
            \App\Repositories\RolePermission\PermissionRepository::class
        );

        $this->app->bind(
            \App\Repositories\MasterData\CategoryRepositoryInterface::class,
            \App\Repositories\MasterData\CategoryRepository::class
        );

        $this->app->bind(
            \App\Repositories\MasterData\TagRepositoryInterface::class,
            \App\Repositories\MasterData\TagRepository::class
        );

        $this->app->bind(
            \App\Repositories\MasterData\IngredientRepositoryInterface::class,
            \App\Repositories\MasterData\IngredientRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
