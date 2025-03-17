<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Descs extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_descs';

    protected $fillables = [
        'bireme_code',
        'name',
        'description'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tb_event_descs', 'descs_id', 'event_id');
    }
}
