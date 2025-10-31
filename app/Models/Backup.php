<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'created_by',
        'filename',
        'path',
        'size',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * المستخدم الذي أنشأ النسخة الاحتياطية
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * الحصول على الحجم بصيغة قابلة للقراءة
     */
    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * التحقق من وجود الملف
     */
    public function fileExists(): bool
    {
        return \Storage::disk('backups')->exists($this->path);
    }

    /**
     * الحصول على مسار الملف الكامل
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/backups/' . $this->path);
    }
}

