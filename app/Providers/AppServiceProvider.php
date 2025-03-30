<?php

namespace App\Providers;

use App\Models\Permission;
use App\Repository\PermissionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(PermissionRepository $permissionRepository): void
    {
        JsonResource::withoutWrapping();
        Model::preventLazyLoading(!app()->isProduction());
    }
}
