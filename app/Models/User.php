<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class User extends AuthenticatableUser implements \Illuminate\Contracts\Auth\Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'user_type',
        'specialty',
        'password_hash',
        'salary',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
