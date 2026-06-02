<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'user_id', // Added user_id to link to User model
        'student_id',
        'visitor_name',
        'phone',
        'entry_time',
        'exit_time',
        'purpose',
        'status', // Added status
        'visit_date', // Added visit_date
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
