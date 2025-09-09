<?php

namespace App\Services\Checkout;

use App\Models\{Cart, Order, OrderItem, MerchantLedgerEntry, MerchantProfile, Product, User};
use Illuminate\Support\Facades\DB;

class CreateOrderFromCart
{
    public function handle(Cart $cart, array $opts = []): Order
    {
        return DB::transaction(function () use ($cart, $opts) {

            $paymentMethod = $opts['payment_method'] ?? 'cash_on_delivery';

            // 1) إنشاء Order مبدئي
            $order = Order::create([
                'user_id'         => $cart->user_id,
                'user_address_id' => $opts['user_address_id'] ?? $cart->user_address_id,
                'payment_method'  => $paymentMethod,
                'status'          => $paymentMethod === 'card' ? 'paid' : 'pending',
                'subtotal'        => 0,
                'shipping_total'  => 0,
                'discount_total'  => 0,
                'grand_total'     => 0,
            ]);

            $subtotal = 0;

            // 2) عناصر الطلب + حساب العمولة وصافي التاجر
            foreach ($cart->items as $ci) {
                /** @var Product $product */
                $product   = $ci->product;
                $merchant  = $product->brand->user; // صاحب المنتج
                $qty       = $ci->quantity;
                $unit      = $product->price;

                // نسبة عمولة (ثابت افتراضي أو من Profile التاجر إن وجدت)
                $commissionRate   = optional($merchant->merchantProfile)->default_commission_rate ?? 0.15;
                $lineTotal        = round($unit * $qty, 2);
                $commissionAmount = round($lineTotal * $commissionRate, 2);
                $payoutAmount     = round($lineTotal - $commissionAmount, 2);

                $item = OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'merchant_id'       => $merchant->id,
                    'quantity'          => $qty,
                    'unit_price'        => $unit,
                    'commission_rate'   => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'payout_amount'     => $payoutAmount,
                ]);

                // 3) إنشاء قيد دفتر بحسب وسيلة الدفع
                if ($paymentMethod === 'cash_on_delivery') {
                    // التاجر مَدين لك بقيمة عمولتك
                    MerchantLedgerEntry::create([
                        'merchant_id'   => $merchant->id,
                        'order_id'      => $order->id,
                        'order_item_id' => $item->id,
                        'direction'     => 'receivable_from_merchant',
                        'amount'        => $commissionAmount,
                        'status'        => 'pending',
                    ]);
                } else {
                    // card/online: أنت مَدين للتاجر بصافي حصّته
                    MerchantLedgerEntry::create([
                        'merchant_id'   => $merchant->id,
                        'order_id'      => $order->id,
                        'order_item_id' => $item->id,
                        'direction'     => 'payable_to_merchant',
                        'amount'        => $payoutAmount,
                        'status'        => 'pending',
                    ]);
                }

                $subtotal += $lineTotal;
            }

            // 4) تحديث الإجماليات وإغلاق السلة
            $order->update([
                'subtotal'     => $subtotal,
                'grand_total'  => $subtotal, // عدّلها لاحقاً لو عندك شحن/خصم
            ]);

            $cart->delete();

            return $order;
        });
    }
}
