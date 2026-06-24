<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuWindow extends Model
{
    protected $fillable = [
        'label', 'day_of_week', 'start_time', 'end_time',
        'is_active', 'triggered_manually', 'is_recurring',
        'description', 'estimated_participants',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'triggered_manually' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    public function analytics()
    {
        return $this->hasMany(WindowAnalytic::class);
    }

    public function notifications()
    {
        return $this->hasMany(NotificationQueue::class);
    }

    public function getLatestAnalytic()
    {
        return $this->analytics()->latest()->first();
    }

    public function getDurationMinutesAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        return $end->diffInMinutes($start);
    }
}
