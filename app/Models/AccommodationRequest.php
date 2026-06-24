<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationRequest extends Model
{
    protected $fillable = [
        'student_ref_id', 'student_name', 'department', 'category',
        'notes', 'modified_schedule', 'status', 'admin_notes', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved'     => 'bg-emerald-500/20 text-emerald-400',
            'denied'       => 'bg-red-500/20 text-red-400',
            'under_review' => 'bg-amber-500/20 text-amber-400',
            default        => 'bg-slate-500/20 text-slate-400',
        };
    }
}
