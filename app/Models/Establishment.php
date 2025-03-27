<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establishment extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_establishments';

    protected $fillable = [
        'name',
        'cnes',
        'management',
        'legal_nature',
        'sus',
        'datacnes_id'
    ];

    // RELATIONSHIPS
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tb_establishment_users', 'establishment_id', 'user_id')
            ->using(EstablishmentUser::class)
            ->wherePivotNull('deleted_at')
            ->withPivot('primary_bond', 'cbo_id');
    }
}
