<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who created the notification.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include recent notifications (last 30 days).
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if notification is read.
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Get time ago format for notification.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon based on notification type.
     */
    public function getIconAttribute()
    {
        $icons = [
            'user_registered' => 'fas fa-user-plus',
            'user_updated' => 'fas fa-user-edit',
            'user_deleted' => 'fas fa-user-minus',
            'system_alert' => 'fas fa-exclamation-triangle',
            'new_message' => 'fas fa-envelope',
            'report_generated' => 'fas fa-file',
            'login_alert' => 'fas fa-sign-in-alt',
            'password_changed' => 'fas fa-lock',
            'profile_updated' => 'fas fa-user-cog'
        ];

        return $icons[$this->type] ?? 'fas fa-bell';
    }

    /**
     * Get badge color based on notification type.
     */
    public function getBadgeColorAttribute()
    {
        $colors = [
            'user_registered' => 'success',
            'user_updated' => 'info',
            'user_deleted' => 'danger',
            'system_alert' => 'warning',
            'new_message' => 'primary',
            'report_generated' => 'secondary'
        ];

        return $colors[$this->type] ?? 'secondary';
    }
}