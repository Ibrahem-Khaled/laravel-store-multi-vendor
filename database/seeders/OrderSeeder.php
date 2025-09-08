<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, OrderItem, Product, MerchantLedgerEntry, User};

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 50 عميل
        $customers = User::factory(50)->customer()->create();

        // 120 طلب
        for ($i = 0; $i < 120; $i++) {
            DB::transaction(function () use ($customers) {
                $customer = $customers->random();

                // 30% COD والباقي card
                $pm = fake()->boolean(30) ? 'cash_on_delivery' : 'card';

                /** @var Order $order */
                $order = Order::factory()->create([
                    'user_id'        => $customer->id,
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
                    $rate      = optional($merchant->merchantProfile)->default_commission_rate ?? 0.15;
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
            });
        }
    }
}
