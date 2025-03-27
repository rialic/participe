<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasIdWithUuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, HasIdWithUuids { HasIdWithUuids::uniqueIds insteadof HasUuids; }

    protected $table = 'tb_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'sex',
        'cpf',
        'cns',
        'verified_at',
        'password',
        'type_professional'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // GETTERS
    public function getFirstNameAttribute(): string
    {
        return substr($this->name, 0, strpos($this->name, ' '));
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'tb_event_participants', 'user_id', 'event_id')
            ->withPivot('rating_event', 'rating_event_schedule', 'hint', 'rated_at');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tb_user_roles', 'user_id', 'role_id');
    }

    public function cbos(): BelongsToMany
    {
        return $this->belongsToMany(Cbo::class, 'tb_establishment_users', 'user_id', 'cbo_id')
            ->using(EstablishmentUser::class)
            ->withPivot('primary_bond', 'establishment_id');
    }

    public function establishments(): BelongsToMany
    {
        return $this->belongsToMany(Establishment::class, 'tb_establishment_users', 'user_id', 'establishment_id')
            ->using(EstablishmentUser::class)
            ->withPivot('primary_bond', 'cbo_id');
    }
}
