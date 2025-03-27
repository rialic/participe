<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_events';

    protected $fillable = [
        'name',
        'start_at',
        'start_minutes_additions',
        'end_at',
        'end_minutes_additions',
        'organization',
        'room_link',
        'workload',
        'created_by',
        'deleted_by',
    ];

    protected $appends = [
        'start_at_formatted',
        'end_at_formatted',
        'workload_formatted'
    ];

    public function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    // GETTERS
    public function getStartAtFormattedAttribute()
    {
        if ($this->start_at) {
            return $this->start_at->format('d/m/Y');
        }

        return $this->start_at;
    }

    public function getEndAtFormattedAttribute()
    {
        if ($this->end_at) {
            return $this->end_at->format('d/m/Y');
        }

        return $this->end_at;
    }

    public function getWorkloadFormattedAttribute()
    {
        if ($this->start_at) {
            return $this->start_at->diffForHumans($this->end_at, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'join' => true, 'parts' => 2]);
        }

        return $this->start_at;
    }

    // RELATIONSHIPS
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'tb_event_participants', 'event_id', 'user_id');
    }

    public function descs()
    {
        return $this->belongsToMany(Descs::class, 'tb_event_descs', 'event_id', 'descs_id');
    }
}
