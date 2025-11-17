<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryFeeController extends Controller
{
    /**
     * عرض صفحة إدارة تسعيرة التوصيل
     */
    public function index()
    {
        $settings = [
            'base_fee' => Setting::get('delivery_base_fee', '10.00'),
            'distance_fee_per_km' => Setting::get('delivery_distance_fee_per_km', '0.5'),
            'car_multiplier' => Setting::get('delivery_car_multiplier', '1.0'),
            'motorcycle_multiplier' => Setting::get('delivery_motorcycle_multiplier', '0.8'),
            'bicycle_multiplier' => Setting::get('delivery_bicycle_multiplier', '0.6'),
            'min_fee' => Setting::get('delivery_min_fee', '5.00'),
            'max_fee' => Setting::get('delivery_max_fee', '50.00'),
        ];

        return view('dashboard.driver-management.delivery-fee', compact('settings'));
    }

    /**
     * حفظ إعدادات تسعيرة التوصيل
     */
    public function store(Request $request)
    {
        $request->validate([
            'base_fee' => 'required|numeric|min:0',
            'distance_fee_per_km' => 'required|numeric|min:0',
            'car_multiplier' => 'required|numeric|min:0|max:2',
            'motorcycle_multiplier' => 'required|numeric|min:0|max:2',
            'bicycle_multiplier' => 'required|numeric|min:0|max:2',
            'min_fee' => 'required|numeric|min:0',
            'max_fee' => 'required|numeric|min:0',
        ], [
            'base_fee.required' => 'الرسوم الأساسية مطلوبة',
            'base_fee.numeric' => 'الرسوم الأساسية يجب أن تكون رقماً',
            'base_fee.min' => 'الرسوم الأساسية يجب أن تكون أكبر من أو تساوي صفر',
            'distance_fee_per_km.required' => 'رسوم المسافة مطلوبة',
            'distance_fee_per_km.numeric' => 'رسوم المسافة يجب أن تكون رقماً',
            'distance_fee_per_km.min' => 'رسوم المسافة يجب أن تكون أكبر من أو تساوي صفر',
            'car_multiplier.required' => 'مضاعف السيارة مطلوب',
            'car_multiplier.numeric' => 'مضاعف السيارة يجب أن يكون رقماً',
            'car_multiplier.min' => 'مضاعف السيارة يجب أن يكون أكبر من أو يساوي صفر',
            'car_multiplier.max' => 'مضاعف السيارة يجب أن يكون أقل من أو يساوي 2',
            'motorcycle_multiplier.required' => 'مضاعف الدراجة النارية مطلوب',
            'motorcycle_multiplier.numeric' => 'مضاعف الدراجة النارية يجب أن يكون رقماً',
            'motorcycle_multiplier.min' => 'مضاعف الدراجة النارية يجب أن يكون أكبر من أو يساوي صفر',
            'motorcycle_multiplier.max' => 'مضاعف الدراجة النارية يجب أن يكون أقل من أو يساوي 2',
            'bicycle_multiplier.required' => 'مضاعف الدراجة الهوائية مطلوب',
            'bicycle_multiplier.numeric' => 'مضاعف الدراجة الهوائية يجب أن يكون رقماً',
            'bicycle_multiplier.min' => 'مضاعف الدراجة الهوائية يجب أن يكون أكبر من أو يساوي صفر',
            'bicycle_multiplier.max' => 'مضاعف الدراجة الهوائية يجب أن يكون أقل من أو يساوي 2',
            'min_fee.required' => 'الحد الأدنى مطلوب',
            'min_fee.numeric' => 'الحد الأدنى يجب أن يكون رقماً',
            'min_fee.min' => 'الحد الأدنى يجب أن يكون أكبر من أو يساوي صفر',
            'max_fee.required' => 'الحد الأقصى مطلوب',
            'max_fee.numeric' => 'الحد الأقصى يجب أن يكون رقماً',
            'max_fee.min' => 'الحد الأقصى يجب أن يكون أكبر من أو يساوي صفر',
        ]);

        // التحقق من أن الحد الأدنى أقل من الأقصى
        if ($request->min_fee > $request->max_fee) {
            return back()->withErrors(['min_fee' => 'الحد الأدنى يجب أن يكون أقل من الحد الأقصى'])->withInput();
        }

        try {
            DB::beginTransaction();

            Setting::set('delivery_base_fee', $request->base_fee);
            Setting::set('delivery_distance_fee_per_km', $request->distance_fee_per_km);
            Setting::set('delivery_car_multiplier', $request->car_multiplier);
            Setting::set('delivery_motorcycle_multiplier', $request->motorcycle_multiplier);
            Setting::set('delivery_bicycle_multiplier', $request->bicycle_multiplier);
            Setting::set('delivery_min_fee', $request->min_fee);
            Setting::set('delivery_max_fee', $request->max_fee);

            DB::commit();

            return redirect()->route('admin.delivery-fee.index')
                ->with('success', 'تم حفظ إعدادات تسعيرة التوصيل بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ الإعدادات: ' . $e->getMessage()])->withInput();
        }
    }
}
