<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cbo extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_cbos';

    protected $fillable = [
        'name',
        'code'
    ];

    // RELATIONSHIP
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tb_establishment_users', 'cbo_id', 'user_id');
    }
}
