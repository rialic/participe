<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_permissions';

    protected $fillable = [
        'name',
    ];

    // RELATIONSHIPS
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tb_role_permissions', 'permission_id', 'role_id');
    }
}
