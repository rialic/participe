<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MacroZone extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_macro_zones';

    protected $fillable = [
        'name'
    ];

    // RELATIONSHIPS
    public function microZones()
    {
        return $this->hasMany(MicroZone::class);
    }
}
