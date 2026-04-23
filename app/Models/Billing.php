<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'billing_date',
        'description',
        'amount',
        'doctor_percent',
        'doctor_share',
        'clinic_share',
        'status',
        'notes',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(Doctor::class); }
}