<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends DBRepository implements UserInterface
{
    protected function model()
    {
        return User::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }

    public function filterByEmail($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }
}