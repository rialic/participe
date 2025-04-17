<?php

namespace App\ServiceLayer;

use App\Repository\DashboardRepository;
use App\ServiceLayer\Base\ServiceResource;

class DashboardServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly DashboardRepository $dashboardRepository
    )
    {
        $this->repository = $dashboardRepository;
    }

    public function dashboard()
    {
        $participantsCount = $this->repository->participantsCount();
        $webClassCountScheduled = $this->repository->webClassCountScheduled();
        $webClassCountDone = $this->repository->webClassCountDone();
        $participantsAvg = $this->repository->participantsAvg();
        $participantsAvgCount = $this->repository->participantsAvgCount();
        $citiesCount = $this->repository->citiesCount();

        $participantsCount = collect($participantsCount)->reduce(fn($acc, $total, $key) => [...$acc, ['name' => $key, 'count' => (int) $total]], []);
        $webClassCountScheduled = collect($webClassCountScheduled)->reduce(fn($acc, $total, $key) => [...$acc, ['name' => $key, 'count' => (int) $total]], []);
        $webClassCountDone = collect($webClassCountDone)->reduce(fn($acc, $total, $key) => [...$acc, ['name' => $key, 'count' => (int) $total]], []);
        $participantsAvg = collect($participantsAvg)->reduce(fn($acc, $total, $key) => [...$acc, ['name' => $key, 'count' => (int) $total]], []);
        $participantsAvgCount = (int) $participantsAvgCount[0];
        $citiesCount = collect($citiesCount)->reduce(fn($acc, $total, $key) => [...$acc, ['name' => $key, 'count' => (int) $total]], []);

        return [
            'webclass' => [
                'participants_count' => $participantsCount,
                'webclass_count_scheduled' => $webClassCountScheduled,
                'webclass_count_done' => $webClassCountDone,
                'participants_avg' => $participantsAvg,
                'participants_avg_count' => $participantsAvgCount,
                'cities_count' => $citiesCount
            ],
            'timestamp' => ucfirst(now()->translatedFormat('F/Y'))
        ];
    }
}