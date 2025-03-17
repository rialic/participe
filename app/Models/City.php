<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_cities';

    protected $fillable = [
        'name',
        'status',
        'datacnes_id',
        'macro_region_id',
        'micro_region_id',
        'state_id'
    ];

    // RELATIONSHIPS
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function microZone()
    {
        return $this->belongsTo(MicroZone::class, 'state_id');
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }
}
