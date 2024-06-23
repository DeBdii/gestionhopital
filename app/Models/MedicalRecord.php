<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'diagnosis',
        'treatment_history',
        'test_results',
        'prescriptions',
    ];

    // Define a relationship with the Patient model
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}