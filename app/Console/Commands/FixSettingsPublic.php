<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class FixSettingsPublic extends Command
{
    protected $signature = 'settings:fix-public';
    protected $description = 'Ensure privacy policy and terms of service are public';

    public function handle()
    {
        $publicKeys = ['privacy_policy', 'terms_of_service', 'about_us'];

        foreach ($publicKeys as $key) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['is_public' => true]);
                $this->info("تم تحديث {$key} إلى public");
            } else {
                $this->warn("{$key} غير موجود");
            }
        }

        $this->info('تم!');
    }
}

