<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Authenticatable implements AuthenticatableContract
{
    // Ensure primary key is defined
    protected $primaryKey = 'id';

    // Ensure table name is correctly defined
    protected $table = 'users';

    // Other model properties and relationships

    protected $fillable = [
        'name', 'user_type', 'email', 'specialty', 'password', 'salary', 'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
