<?php

namespace App\Http\Models\v1;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'id',
        'name',
        'is_active',
        'type_user_id',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    public function type_user()
    {
        return $this->hasOne('App\Http\Models\v1\User_type', 'id', 'type_user_id');
    }
}
