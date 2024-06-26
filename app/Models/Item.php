<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'quantity', 'description', 'dosage'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_items', 'item_id', 'department_id')->withTimestamps();
    }
}
