<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Neighborhood;
class CityNeighborhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cities = [
            'طرابلس',
            'بنغازي',
            'مصراتة',
            'سبها',
            'البيضاء',
            'درنة',
            'الزاوية',
            'زليتن',
            'الخمس',
            'طبرق'
        ];

        $neighborhoods = [
            ['المدينة القديمة', 'سوق الجمعة', 'باب بن غشير'],
            ['الليثي', 'السلماني', 'البركة'],
            ['زاوية المحجوب', 'الغيران', 'الدافنية'],
            ['المنشية', 'الجديد', 'عبد الكافي'],
            ['شحات', 'سيدي رافع', 'الزهور'],
            ['الجبيلة', 'المدينة القديمة', 'الساحل الشرقي'],
            ['الحرشة', 'بئر الغنم', 'جميلة'],
            ['ماجر', 'المدنية', 'الدافنية'],
            ['حي الجهاد', 'المدينة القديمة', 'البيفي'],
            ['باب درنة', 'الحي الصناعي', 'المرقب'],
        ];

        foreach ($cities as $index => $cityName) {
            $city = City::firstOrCreate(['name' => $cityName]);

            foreach ($neighborhoods[$index] as $neighborhoodName) {
                Neighborhood::firstOrCreate([
                    'name' => $neighborhoodName,
                    'city_id' => $city->id,
                ]);
            }
        }
    }
}
