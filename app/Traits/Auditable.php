<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the trait - Register model events
     */
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            static::logAudit($model, 'created');
        });

        static::updated(function ($model) {
            static::logAudit($model, 'updated');
        });

        static::deleted(function ($model) {
            static::logAudit($model, 'deleted');
        });

        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::restored(function ($model) {
                static::logAudit($model, 'restored');
            });
        }
    }

    /**
     * تسجيل عملية التدقيق
     */
    protected static function logAudit($model, string $action): void
    {
        $user = Auth::user();
        $oldValues = [];
        $newValues = [];
        $changedFields = [];

        if ($action === 'updated') {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges();
            $changedFields = array_keys($newValues);
        } elseif ($action === 'created') {
            $newValues = $model->getAttributes();
            $changedFields = array_keys($newValues);
        } elseif ($action === 'deleted' || $action === 'force_deleted') {
            $oldValues = $model->getAttributes();
        }

        // إزالة الحقول الحساسة من السجل
        $hiddenFields = ['password', 'remember_token'];
        $oldValues = array_diff_key($oldValues, array_flip($hiddenFields));
        $newValues = array_diff_key($newValues, array_flip($hiddenFields));
        $changedFields = array_diff($changedFields, $hiddenFields);

        AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
            'changed_fields' => !empty($changedFields) ? $changedFields : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ]);
    }

    /**
     * الحصول على سجل التدقيق للنموذج
     */
    public function modelAuditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * الحصول على آخر سجل تدقيق
     */
    public function latestAuditLog()
    {
        return $this->morphOne(AuditLog::class, 'auditable')->latestOfMany();
    }
}

