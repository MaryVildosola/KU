<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowAnalytic extends Model
{
    protected $fillable = [
        'ku_window_id', 'participants', 'notifications_held',
        'notifications_released', 'avg_cri_improvement',
        'faculty_participation_rate', 'student_participation_rate',
        'emergency_bypasses', 'window_started_at', 'window_ended_at',
    ];

    protected $casts = [
        'window_started_at' => 'datetime',
        'window_ended_at'   => 'datetime',
    ];

    public function window()
    {
        return $this->belongsTo(KuWindow::class, 'ku_window_id');
    }
}
