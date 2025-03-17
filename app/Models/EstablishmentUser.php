<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EstablishmentUser extends Pivot
{
    protected $table = 'tb_establishment_users';

    protected $fillable = [
        'establishment_id',
        'user_id',
        'cbo_id',
        'primary_bond',
    ];

    public function cbo(): BelongsTo
    {
        return $this->belongsTo(Cbo::class, 'cbo_id');
    }
}
