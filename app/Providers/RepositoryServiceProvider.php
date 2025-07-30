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
    EventReportInterface,
    MacroZoneInterface,
    MicroZoneInterface,
    ModuleInterface,
    ParticipantInterface,
    PermissionInterface,
    RoleInterface,
    SmartInterface,
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
    EventReportRepository,
    EventRepository,
    MacroZoneRepository,
    MicroZoneRepository,
    ModuleRepository,
    ParticipantRepository,
    PermissionRepository,
    RoleRepository,
    SmartRepository,
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
        $this->app->bind(MacroZoneInterface::class, MacroZoneRepository::class);
        $this->app->bind(MicroZoneInterface::class, MicroZoneRepository::class);
        $this->app->bind(EstablishmentInterface::class, EstablishmentRepository::class);
        $this->app->bind(EventInterface::class, EventRepository::class);
        $this->app->bind(EventReportInterface::class, EventReportRepository::class);
        $this->app->bind(ModuleInterface::class, ModuleRepository::class);
        $this->app->bind(ParticipantInterface::class, ParticipantRepository::class);
        $this->app->bind(SmartInterface::class, SmartRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
