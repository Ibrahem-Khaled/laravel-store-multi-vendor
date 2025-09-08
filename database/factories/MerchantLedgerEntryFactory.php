<?php

namespace Database\Factories;

use App\Models\MerchantLedgerEntry;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchantLedgerEntry>
 */
class MerchantLedgerEntryFactory extends Factory
{

    protected $model = MerchantLedgerEntry::class;

    public function definition(): array
    {
        $item = OrderItem::factory()->create();
        // نحدّد الاتجاه حسب طريقة دفع الطلب
        $direction = $item->order->payment_method === 'cash_on_delivery'
            ? 'receivable_from_merchant'   // عمولتك على التاجر
            : 'payable_to_merchant';       // صافي للتاجر

        $amount = $direction === 'receivable_from_merchant'
            ? $item->commission_amount
            : $item->payout_amount;

        return [
            'merchant_id'   => $item->merchant_id,
            'order_id'      => $item->order_id,
            'order_item_id' => $item->id,
            'direction'     => $direction,
            'amount'        => $amount,
            'status'        => 'pending',
            'due_date'      => now()->addDays(7),
        ];
    }
}
