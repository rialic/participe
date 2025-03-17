<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CboInterface {
  public function filterByName($query, $data, $field): Builder;
  public function filterByCode($query, $data, $field): Builder;
}