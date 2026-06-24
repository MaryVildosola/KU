<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FomoRiskScore extends Model
{
    protected $fillable = [
        'composite_score', 'notification_pressure', 'deadline_clustering',
        'midnight_activity', 'after_hours_faculty', 'risk_level',
        'total_users_analyzed', 'computed_at',
    ];

    protected $casts = [
        'computed_at' => 'datetime',
    ];

    public function getRiskColorAttribute(): string
    {
        return match($this->risk_level) {
            'low'      => 'text-emerald-400',
            'moderate' => 'text-amber-400',
            'high'     => 'text-orange-400',
            'critical' => 'text-red-400',
            default    => 'text-slate-400',
        };
    }
}
