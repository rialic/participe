<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadOf HasUuids; }

    protected $table = 'tb_states';

    protected $fillable = [
        'name',
        'acronym'
    ];

    // RELATIONSHIPS
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
