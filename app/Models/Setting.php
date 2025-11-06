<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
        'order',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * الحصول على قيمة إعداد معين
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * حفظ أو تحديث إعداد
     */
    public static function set($key, $value = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );

        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');

        return $setting;
    }

    /**
     * الحصول على جميع الإعدادات من مجموعة معينة
     */
    public static function getGroup($group)
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return static::where('group', $group)
                ->orderBy('order')
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * الحصول على جميع الإعدادات كمصفوفة
     */
    public static function getAll()
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * حفظ عدة إعدادات مرة واحدة
     */
    public static function setMany(array $settings)
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }

    /**
     * مسح الكاش
     */
    public static function clearCache()
    {
        Cache::forget('settings.all');
        $groups = ['general', 'social', 'privacy', 'terms', 'about', 'seo', 'notifications'];
        foreach ($groups as $group) {
            Cache::forget("settings.group.{$group}");
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            static::clearCache();
        });

        static::deleted(function ($setting) {
            static::clearCache();
        });
    }

    /**
     * Scope للحصول على الإعدادات العامة فقط
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope للحصول على إعدادات مجموعة معينة
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
