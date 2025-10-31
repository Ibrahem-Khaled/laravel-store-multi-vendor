<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'changed_fields',
        'description',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * المستخدم الذي قام بالعملية
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * النموذج المرتبط بالسجل
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * الحصول على وصف مفصل للعملية
     */
    public function getDescriptionAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        $userName = $this->user ? $this->user->name : 'نظام';
        $action = $this->getActionLabel();
        $modelName = class_basename($this->auditable_type);

        return "{$userName} {$action} {$modelName} #{$this->auditable_id}";
    }

    /**
     * الحصول على اسم العملية بالعربية
     */
    public function getActionLabel(): string
    {
        return match($this->action) {
            'created' => 'أنشأ',
            'updated' => 'عدّل',
            'deleted' => 'حذف',
            'restored' => 'استعاد',
            'force_deleted' => 'حذف نهائياً',
            default => $this->action,
        };
    }

    /**
     * الحصول على اسم النموذج بالعربية
     */
    public function getModelLabel(): string
    {
        return match(class_basename($this->auditable_type)) {
            'User' => 'مستخدم',
            'Product' => 'منتج',
            'Category' => 'تصنيف',
            'SubCategory' => 'تصنيف فرعي',
            'Brand' => 'علامة تجارية',
            'Order' => 'طلب',
            'OrderItem' => 'عنصر طلب',
            'MerchantProfile' => 'تاجر',
            'Review' => 'تقييم',
            'Notification' => 'إشعار',
            'SlideShow' => 'سلايدشو',
            'City' => 'مدينة',
            'Neighborhood' => 'حي',
            'Feature' => 'ميزة',
            default => class_basename($this->auditable_type),
        };
    }

    /**
     * نطاق البحث
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? null, function ($q, $userId) {
            $q->where('user_id', $userId);
        })
        ->when($filters['action'] ?? null, function ($q, $action) {
            $q->where('action', $action);
        })
        ->when($filters['auditable_type'] ?? null, function ($q, $type) {
            $q->where('auditable_type', $type);
        })
        ->when($filters['date_from'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '>=', $date);
        })
        ->when($filters['date_to'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '<=', $date);
        })
        ->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        });
    }
}

