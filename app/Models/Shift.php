<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'shift_datetime',
        'doctor_id',
        'administrator_id',
        'employee_id',
        'employee_type',
    ];

    // Define relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}