<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'department_items', 'department_id', 'item_id')->withTimestamps();
    }
}
