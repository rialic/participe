<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CertificateInterface
{
    public function filterByCpf($query, $data, $field): Builder;
}