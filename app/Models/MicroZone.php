<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MicroZone extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_micro_zones';

    protected $fillable = [
        'name'
    ];

    // RELATIONSHIPS
    public function macroZone()
    {
        return $this->belongsTo(MacroZone::class, 'macro_zone_id');
    }
}
