<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Submodule extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_submodules';

    protected $filable = [
        'name'
    ];

    // RELATIONSHIPS
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
