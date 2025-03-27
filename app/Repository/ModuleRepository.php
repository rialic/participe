<?php

namespace App\Repository;

use App\Models\Module;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\ModuleInterface;
use Illuminate\Database\Eloquent\Builder;

class ModuleRepository extends DBRepository implements ModuleInterface
{
    protected function model()
    {
        return Module::class;
    }

    public function query(array $params = []): Builder
    {
        $params['orderBy'] = 'order';
        $params['direction'] = 'asc';
        $query = parent::query($params);

        return $query->with('submodules:uuid,name,module_id');
    }
}