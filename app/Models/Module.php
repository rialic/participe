<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_modules';

    protected $fillable = [
        'name'
    ];

    // RELATIONSHIPS
    public function submodules()
    {
        return $this->hasMany(Submodule::class, 'module_id');
    }
}
