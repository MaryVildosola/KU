<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'department', 'position', 'avatar_initials',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool     { return $this->role === 'admin'; }
    public function isFaculty(): bool   { return $this->role === 'faculty'; }
    public function isResearcher(): bool { return $this->role === 'researcher'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'      => 'Administrator',
            'faculty'    => 'Faculty',
            'researcher' => 'Researcher',
            default      => 'User',
        };
    }

    // Relationships
    public function shield()
    {
        return $this->hasOne(FacultyShield::class);
    }
}
