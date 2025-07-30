<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface MacroZoneInterface
{
  public function filterByName($query, $data, $field): Builder;
}