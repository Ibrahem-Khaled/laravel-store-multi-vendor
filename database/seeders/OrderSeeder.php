<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, OrderItem, Product, MerchantLedgerEntry, User, Driver, DriverOrder, UserAddress};

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 50 عميل
        $customers = User::factory(50)->customer()->create();
        
        // الحصول على السواقين المتاحين
        $drivers = Driver::where('is_active', true)->get();
        
        if ($drivers->isEmpty()) {
            $this->command->warn('لا يوجد سواقين متاحين. يرجى تشغيل DriverSeeder أولاً.');
            return;
        }

        // 120 طلب
        for ($i = 0; $i < 120; $i++) {
            DB::transaction(function () use ($customers, $drivers) {
                $customer = $customers->random();
                $driver = $drivers->random();

                // إنشاء عنوان للعميل إذا لم يكن لديه واحد
                $userAddress = $customer->addresses()->first();
                if (!$userAddress) {
                    $userAddress = UserAddress::create([
                        'user_id' => $customer->id,
                        'type' => 'home',
                        'address_line_1' => fake()->streetAddress(),
                        'city' => fake()->randomElement(['الرياض', 'جدة', 'الدمام', 'المدينة المنورة', 'مكة المكرمة']),
                        'neighborhood' => fake()->randomElement(['الحي الشمالي', 'الحي الجنوبي', 'الحي الشرقي', 'الحي الغربي', 'الحي المركزي']),
                        'address' => fake()->address(),
                        'postal_code' => fake()->postcode(),
                    ]);
                }

                // 30% COD والباقي card
                $pm = fake()->boolean(30) ? 'cash_on_delivery' : 'card';

                /** @var Order $order */
                $order = Order::factory()->create([
                    'user_id'        => $customer->id,
                    'user_address_id' => $userAddress->id,
                    'payment_method' => $pm,
                    'status'         => $pm === 'card' ? 'paid' : 'pending',
                ]);

                $itemsCount = fake()->numberBetween(1, 5);
                $subtotal   = 0;

                for ($k = 0; $k < $itemsCount; $k++) {
                    $product   = Product::inRandomOrder()->first();
                    $qty       = fake()->numberBetween(1, 3);
                    $unit      = $product->price;
                    $lineTotal = round($unit * $qty, 2);

                    $merchant  = $product->brand->user;
                    // نسبة عمولة: أولوية للتصنيف، ثم التاجر، ثم الافتراضي
                    $category = $product->subCategory->category ?? null;
                    $rate = $category?->commission_rate 
                        ?? optional($merchant->merchantProfile)->default_commission_rate 
                        ?? 0.15;
                    $commission = round($lineTotal * $rate, 2);
                    $payout    = round($lineTotal - $commission, 2);

                    $item = OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $product->id,
                        'merchant_id'       => $merchant->id,
                        'quantity'          => $qty,
                        'unit_price'        => $unit,
                        'commission_rate'   => $rate,
                        'commission_amount' => $commission,
                        'payout_amount'     => $payout,
                    ]);

                    // قيود دفتر حسب وسيلة الدفع
                    if ($pm === 'cash_on_delivery') {
                        MerchantLedgerEntry::create([
                            'merchant_id'   => $merchant->id,
                            'order_id'      => $order->id,
                            'order_item_id' => $item->id,
                            'direction'     => 'receivable_from_merchant', // عمولتك من التاجر
                            'amount'        => $commission,
                            'status'        => 'pending',
                            'due_date'      => now()->addDays(7),
                        ]);
                    } else {
                        MerchantLedgerEntry::create([
                            'merchant_id'   => $merchant->id,
                            'order_id'      => $order->id,
                            'order_item_id' => $item->id,
                            'direction'     => 'payable_to_merchant',      // صافي للتاجر
                            'amount'        => $payout,
                            'status'        => 'pending',
                            'due_date'      => now()->addDays(3),
                        ]);
                    }

                    $subtotal += $lineTotal;
                }

                $order->update([
                    'subtotal'    => $subtotal,
                    'grand_total' => $subtotal,
                ]);

                // إنشاء DriverOrder وتعيين سائق للطلب
                $assignedAt = now()->subDays(fake()->numberBetween(0, 30));
                $status = fake()->randomElement(['assigned', 'accepted', 'picked_up', 'delivered', 'cancelled']);
                
                $driverOrderData = [
                    'order_id' => $order->id,
                    'driver_id' => $driver->id,
                    'assigned_by' => User::where('role', 'admin')->first()?->id,
                    'status' => $status,
                    'assignment_type' => fake()->randomElement(['auto', 'manual']),
                    'assigned_at' => $assignedAt,
                    'delivery_fee' => fake()->randomFloat(2, 5, 25),
                ];

                // إضافة التواريخ حسب الحالة
                if (in_array($status, ['accepted', 'picked_up', 'delivered'])) {
                    $driverOrderData['accepted_at'] = $assignedAt->copy()->addMinutes(fake()->numberBetween(5, 30));
                }

                if (in_array($status, ['picked_up', 'delivered'])) {
                    $driverOrderData['picked_up_at'] = $driverOrderData['accepted_at']->copy()->addMinutes(fake()->numberBetween(15, 60));
                }

                if ($status === 'delivered') {
                    $driverOrderData['delivered_at'] = $driverOrderData['picked_up_at']->copy()->addMinutes(fake()->numberBetween(30, 120));
                    
                    // إضافة تقييم عشوائي للسائق (من 3 إلى 5)
                    $order->update([
                        'driver_rating' => fake()->randomFloat(2, 3.0, 5.0)
                    ]);
                }

                if ($status === 'cancelled') {
                    $driverOrderData['cancelled_at'] = $assignedAt->copy()->addMinutes(fake()->numberBetween(10, 60));
                    $driverOrderData['cancellation_reason'] = fake()->randomElement([
                        'السائق غير متاح',
                        'العميل ألغى الطلب',
                        'مشكلة في العنوان',
                        'السائق رفض الطلب'
                    ]);
                }

                DriverOrder::create($driverOrderData);

                // تحديث إحصائيات السائق
                $driver->increment('total_deliveries', $status === 'delivered' ? 1 : 0);
                $driver->update(['current_orders_count' => $driver->driverOrders()->whereIn('status', ['assigned', 'accepted', 'picked_up'])->count()]);
            });
        }

        // تحديث تقييمات السواقين بناءً على التقييمات الفعلية
        $drivers->each(function ($driver) {
            $completedOrders = $driver->driverOrders()
                ->where('status', 'delivered')
                ->whereHas('order', function($query) {
                    $query->whereNotNull('driver_rating');
                })
                ->with('order')
                ->get();

            if ($completedOrders->count() > 0) {
                $avgRating = $completedOrders->avg(function($driverOrder) {
                    return $driverOrder->order->driver_rating;
                });
                $driver->update(['rating' => round($avgRating, 2)]);
            }
        });
    }
}
