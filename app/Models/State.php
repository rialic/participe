<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_states';

    protected $fillable = [
        'name',
        'acronym'
    ];

    // RELATIONSHIPS
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
