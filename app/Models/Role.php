<?php

namespace App\Models;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUuids, HasIdWithUuids, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_roles';

    protected $fillable = [
        'name',
    ];

    // RELATIONSHIPS
    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_user_roles', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'tb_role_permissions', 'role_id', 'permission_id');
    }
}
