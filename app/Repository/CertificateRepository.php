<?php

namespace App\Repository;

use App\Models\Event;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\CertificateInterface;
use Illuminate\Database\Eloquent\Builder;

class CertificateRepository extends DBRepository implements CertificateInterface
{
    protected function model()
    {
        return Event::class;
    }

    public function filterByCpf($query, $data, $field): Builder
    {
        return $query->whereHas('participants', fn($query) => $query->where($field, $data))
        ->with(['participants' => fn($query) => $query->select(['tb_users.uuid', 'tb_users.name', 'rated_at'])->where('cpf', $data)])
        ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) <= current_timestamp()');
    }
}