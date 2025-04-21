<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Interfaces\{
    CboInterface,
    CertificateInterface,
    CityInterface,
    DashboardInterface,
    DescsInterface,
    EstablishmentInterface,
    EventInterface,
    ModuleInterface,
    ParticipantInterface,
    PermissionInterface,
    RoleInterface,
    StateInterface,
    UserInterface
};
use App\Repository\{
    CboRepository,
    CertificateRepository,
    CityRepository,
    DashboardRepository,
    DescsRepository,
    EstablishmentRepository,
    EventRepository,
    ModuleRepository,
    ParticipantRepository,
    PermissionRepository,
    RoleRepository,
    StateRepository,
    UserRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermissionInterface::class, PermissionRepository::class);
        $this->app->bind(CboInterface::class, CboRepository::class);
        $this->app->bind(CertificateInterface::class, CertificateRepository::class);
        $this->app->bind(StateInterface::class, StateRepository::class);
        $this->app->bind(CityInterface::class, CityRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        $this->app->bind(DescsInterface::class, DescsRepository::class);
        $this->app->bind(EstablishmentInterface::class, EstablishmentRepository::class);
        $this->app->bind(EventInterface::class, EventRepository::class);
        $this->app->bind(ModuleInterface::class, ModuleRepository::class);
        $this->app->bind(ParticipantInterface::class, ParticipantRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
