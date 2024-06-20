<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'start_datetime', // Update to nullable datetime
        'end_datetime', // Update to nullable datetime
        'doctor_id',
        'administrator_id',
        'employee_id',
        'employee_type',
    ];

    // Define relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'shift_user');
    }

    public function doctors()
    {
        return $this->users()->where('user_type', 'Doctor');
    }

    public function employees()
    {
        return $this->users()->whereIn('user_type', ['Nurse', 'Receptionist', 'SupportStaff']);
    }
}
