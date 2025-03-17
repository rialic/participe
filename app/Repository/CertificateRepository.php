<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\CertificateInterface;
use Illuminate\Database\Eloquent\Builder;

class CertificateRepository extends DBRepository implements CertificateInterface
{
    protected function model()
    {
        return User::class;
    }

    public function query(array $params = []): Builder
    {
        $query = parent::query($params);

        return $query->with(['events' => function($event) {
            $event->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) <= current_timestamp()');
        }]);
    }

    public function filterByCpf($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }
}