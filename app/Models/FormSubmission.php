<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'registration_form_id',
        'student_number',
        'student_id_no',
        'student_name',
        'parent_name',
        'parent_phone',
        'phone_number',
        'email',
        'semester',
        'department',
        'id_card_photo',
        'application_photos',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'application_photos' => 'array',
        'processed_at' => 'datetime'
    ];

    public function registrationForm()
    {
        return $this->belongsTo(RegistrationForm::class);
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Alias for easier access in views
    public function getProcessedByAttribute()
    {
        return $this->processedByUser;
    }
}
