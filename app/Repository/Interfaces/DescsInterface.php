<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface DescsInterface {
  public function filterByName($query, $data, $field): Builder;
  public function filterByBiremeCode($query, $data, $field): Builder;
}