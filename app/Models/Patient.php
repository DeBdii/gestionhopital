<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'dob', 'gender', 'contact_number', 'address',
    ];

    protected $dates = [
        'dob', // Ensure 'dob' is treated as a date field
    ];

    // Define a relationship with the MedicalRecord model
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
