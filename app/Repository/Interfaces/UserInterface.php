<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface UserInterface {
  public function filterByName($query, $data, $field): Builder;
  public function filterByEmail($query, $data, $field): Builder;
}