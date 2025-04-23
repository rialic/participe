<?php

namespace App\Providers;

use App\Repository\PermissionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
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
        Carbon::setlocale(config('app.locale'));
        JsonResource::withoutWrapping();
        Model::preventLazyLoading(!app()->isProduction());
    }
}
