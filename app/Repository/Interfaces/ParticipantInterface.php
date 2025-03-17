<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ParticipantInterface {
  public function filterByCPF($query, $data, $field): Builder;
}