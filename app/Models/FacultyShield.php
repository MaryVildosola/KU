<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyShield extends Model
{
    protected $fillable = [
        'user_id', 'is_active', 'auto_reply_text',
        'allow_student_crisis_bypass', 'messages_queued_total', 'last_window_queued_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allow_student_crisis_bypass' => 'boolean',
        'last_window_queued_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
