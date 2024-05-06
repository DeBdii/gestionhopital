<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'doctors_id',
        'item_id',
    ];

    // Define relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctors_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'item_id');
    }
}