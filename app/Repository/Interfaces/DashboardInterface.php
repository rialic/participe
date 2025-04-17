<?php

namespace App\Repository\Interfaces;

interface DashboardInterface
{
    public function participantsCount(): array;
    public function webClassCountDone(): array;
    public function webClassCountScheduled(): array;
    public function participantsAvg(): array;
    public function participantsAvgCount(): array;
    public function citiesCount(): array;
}