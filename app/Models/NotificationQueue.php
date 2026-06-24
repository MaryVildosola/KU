<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationQueue extends Model
{
    protected $table = 'notification_queue';

    protected $fillable = [
        'ku_window_id', 'sender', 'recipient_group', 'recipient_count',
        'app_type', 'message', 'held_at', 'released_at',
    ];

    protected $casts = [
        'held_at'     => 'datetime',
        'released_at' => 'datetime',
    ];

    public function window()
    {
        return $this->belongsTo(KuWindow::class, 'ku_window_id');
    }

    public function getIsReleasedAttribute(): bool
    {
        return $this->released_at !== null;
    }
}
