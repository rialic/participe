<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface MicroZoneInterface
{
  public function filterByName($query, $data, $field): Builder;
  public function filterByMacroZone($query, $data, $field): Builder;
}