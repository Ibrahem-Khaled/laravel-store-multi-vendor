<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'subject',
        'message',
        'status',
        'priority',
        'attachment',
        'response',
        'responded_by',
        'responded_at',
        'closed_at',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Boot method - توليد رقم التذكرة تلقائياً
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = static::generateTicketNumber();
            }
        });
    }

    /**
     * توليد رقم تذكرة فريد
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . strtoupper(Str::random(8));
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * علاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة مع المدرس الذي رد
     */
    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * علاقة مع الفئة
     */
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Scope للحالة
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope للأولوية
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope للمستخدم
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope للتذاكر المعلقة
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope للتذاكر المفتوحة
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope للتذاكر المغلقة
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'open' => 'info',
            'in_progress' => 'primary',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'light',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'light',
        };
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'open' => 'مفتوحة',
            'in_progress' => 'قيد المعالجة',
            'resolved' => 'تم الحل',
            'closed' => 'مغلقة',
            default => $this->status,
        };
    }

    /**
     * Get priority label in Arabic
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجلة',
            default => $this->priority,
        };
    }

    /**
     * Check if ticket is closed
     */
    public function isClosed(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Check if ticket has response
     */
    public function hasResponse(): bool
    {
        return !is_null($this->response) && !empty($this->response);
    }

    /**
     * Mark as responded
     */
    public function markAsResponded($userId, $response): void
    {
        $this->update([
            'response' => $response,
            'responded_by' => $userId,
            'responded_at' => now(),
            'status' => 'in_progress',
        ]);
    }

    /**
     * Close ticket
     */
    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    /**
     * Resolve ticket
     */
    public function resolve(): void
    {
        $this->update([
            'status' => 'resolved',
            'closed_at' => now(),
        ]);
    }
}
