<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'notice_photo'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }
}
