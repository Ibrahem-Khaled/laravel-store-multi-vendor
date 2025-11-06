<?php

if (!function_exists('setting')) {
    /**
     * الحصول على قيمة إعداد معين
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * الحصول على جميع الإعدادات أو مجموعة محددة
     *
     * @param string|null $group
     * @return array
     */
    function settings($group = null)
    {
        if ($group) {
            return \App\Models\Setting::getGroup($group);
        }
        return \App\Models\Setting::getAll();
    }
}

