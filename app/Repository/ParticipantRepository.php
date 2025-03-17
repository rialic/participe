<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\ParticipantInterface;
use Illuminate\Database\Eloquent\Builder;

class ParticipantRepository extends DBRepository implements ParticipantInterface
{
    protected function model()
    {
        return User::class;
    }

    public function query(array $params = []): Builder
    {
        $query = parent::query($params);

        return $query->with(['establishments' => fn($establishment) => $establishment->where('primary_bond', true)]);
    }

    public function filterByCpf($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }
}