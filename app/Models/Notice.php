<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'audience', 'attachment'];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
