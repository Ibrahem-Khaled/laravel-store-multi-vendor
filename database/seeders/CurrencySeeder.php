<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            // العملة الأساسية
            [
                'code' => 'USD',
                'name_ar' => 'دولار أمريكي',
                'name_en' => 'US Dollar',
                'symbol' => '$',
                'symbol_ar' => 'دولار',
                'exchange_rate' => 1.0,
                'is_active' => true,
                'is_base_currency' => true,
            ],
            
            // العملات اليمنية
            [
                'code' => 'YER_NEW',
                'name_ar' => 'ريال يمني جديد',
                'name_en' => 'Yemeni Riyal (New)',
                'symbol' => 'ر.ي',
                'symbol_ar' => 'ريال',
                'exchange_rate' => 530.0, // سعر تقريبي - يجب تحديثه حسب السوق
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'YER_OLD',
                'name_ar' => 'ريال يمني قديم',
                'name_en' => 'Yemeni Riyal (Old)',
                'symbol' => 'ر.ي',
                'symbol_ar' => 'ريال',
                'exchange_rate' => 1200.0, // سعر تقريبي - يجب تحديثه حسب السوق
                'is_active' => true,
                'is_base_currency' => false,
            ],
            
            // العملات العربية الأخرى
            [
                'code' => 'SAR',
                'name_ar' => 'ريال سعودي',
                'name_en' => 'Saudi Riyal',
                'symbol' => 'ر.س',
                'symbol_ar' => 'ريال',
                'exchange_rate' => 3.75,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'AED',
                'name_ar' => 'درهم إماراتي',
                'name_en' => 'UAE Dirham',
                'symbol' => 'د.إ',
                'symbol_ar' => 'درهم',
                'exchange_rate' => 3.67,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'KWD',
                'name_ar' => 'دينار كويتي',
                'name_en' => 'Kuwaiti Dinar',
                'symbol' => 'د.ك',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 0.31,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'BHD',
                'name_ar' => 'دينار بحريني',
                'name_en' => 'Bahraini Dinar',
                'symbol' => 'د.ب',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 0.38,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'OMR',
                'name_ar' => 'ريال عماني',
                'name_en' => 'Omani Rial',
                'symbol' => 'ر.ع',
                'symbol_ar' => 'ريال',
                'exchange_rate' => 0.38,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'QAR',
                'name_ar' => 'ريال قطري',
                'name_en' => 'Qatari Riyal',
                'symbol' => 'ر.ق',
                'symbol_ar' => 'ريال',
                'exchange_rate' => 3.64,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'JOD',
                'name_ar' => 'دينار أردني',
                'name_en' => 'Jordanian Dinar',
                'symbol' => 'د.أ',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 0.71,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'EGP',
                'name_ar' => 'جنيه مصري',
                'name_en' => 'Egyptian Pound',
                'symbol' => 'ج.م',
                'symbol_ar' => 'جنيه',
                'exchange_rate' => 30.9,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'IQD',
                'name_ar' => 'دينار عراقي',
                'name_en' => 'Iraqi Dinar',
                'symbol' => 'د.ع',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 1310.0,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'LBP',
                'name_ar' => 'ليرة لبنانية',
                'name_en' => 'Lebanese Pound',
                'symbol' => 'ل.ل',
                'symbol_ar' => 'ليرة',
                'exchange_rate' => 15000.0, // سعر تقريبي - يجب تحديثه حسب السوق
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'LYD',
                'name_ar' => 'دينار ليبي',
                'name_en' => 'Libyan Dinar',
                'symbol' => 'د.ل',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 4.85,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'DZD',
                'name_ar' => 'دينار جزائري',
                'name_en' => 'Algerian Dinar',
                'symbol' => 'د.ج',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 134.5,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'MAD',
                'name_ar' => 'درهم مغربي',
                'name_en' => 'Moroccan Dirham',
                'symbol' => 'د.م',
                'symbol_ar' => 'درهم',
                'exchange_rate' => 10.0,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'TND',
                'name_ar' => 'دينار تونسي',
                'name_en' => 'Tunisian Dinar',
                'symbol' => 'د.ت',
                'symbol_ar' => 'دينار',
                'exchange_rate' => 3.1,
                'is_active' => true,
                'is_base_currency' => false,
            ],
            [
                'code' => 'SYP',
                'name_ar' => 'ليرة سورية',
                'name_en' => 'Syrian Pound',
                'symbol' => 'ل.س',
                'symbol_ar' => 'ليرة',
                'exchange_rate' => 13000.0, // سعر تقريبي - يجب تحديثه حسب السوق
                'is_active' => true,
                'is_base_currency' => false,
            ],
        ];

        foreach ($currencies as $currencyData) {
            Currency::updateOrCreate(
                ['code' => $currencyData['code']],
                $currencyData
            );
        }

        $this->command->info('✅ تم إضافة ' . count($currencies) . ' عملة بنجاح!');
        $this->command->info('📊 العملة الأساسية: USD');
        $this->command->info('🇾🇪 العملات اليمنية: YER_NEW, YER_OLD');
        $this->command->info('🌍 العملات العربية الأخرى: ' . (count($currencies) - 3) . ' عملة');
    }
}
