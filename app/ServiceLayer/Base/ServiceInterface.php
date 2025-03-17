<?php

namespace App\ServiceLayer\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceInterface
{
  public function show(string|array $data): ?object;
  public function index(array $data): Collection|LengthAwarePaginator;
  public function store(array $data): object;
  public function update(string $uuid, array $data): object;
  public function delete(string $uuid): bool;
}