<?php

namespace App\Services\Accounting;

use App\Models\User;
use App\Models\MerchantPayment;
use App\Models\MerchantLedgerEntry;
use Illuminate\Support\Facades\DB;

class MerchantSettlementService
{
    /**
     * إقفال القيود ودفع/تحصيل المبلغ المناسب للتاجر
     *
     * @param User $merchant التاجر
     * @param string $direction payable_to_merchant | receivable_from_merchant
     * @param float $amount المبلغ المطلوب
     * @param array $opts بيانات إضافية: ['method' => 'bank_transfer', 'reference' => 'TXN123']
     * @return MerchantPayment
     */
    public function settleForMerchant(User $merchant, string $direction, float $amount, array $opts = [])
    {
        return DB::transaction(function () use ($merchant, $direction, $amount, $opts) {

            // 1) إنشاء سجل حركة دفع/تحصيل
            $payment = MerchantPayment::create([
                'merchant_id' => $merchant->id,
                'type'        => $direction === 'payable_to_merchant'
                    ? 'payout_to_merchant'
                    : 'collection_from_merchant',
                'amount'      => $amount,
                'method'      => $opts['method'] ?? 'bank_transfer',
                'reference'   => $opts['reference'] ?? null,
                'paid_at'     => now(),
                'meta'        => $opts['meta'] ?? null,
            ]);

            // 2) جلب القيود المفتوحة
            $entries = MerchantLedgerEntry::where('merchant_id', $merchant->id)
                ->where('direction', $direction)
                ->where('status', 'pending')
                ->orderBy('id') // FIFO
                ->lockForUpdate()
                ->get();

            $remaining = $amount;

            foreach ($entries as $entry) {
                if ($remaining <= 0) break;

                if ($entry->amount <= $remaining) {
                    // إقفال القيد بالكامل
                    $entry->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_reference' => $payment->reference
                    ]);
                    $remaining -= $entry->amount;
                } else {
                    // دفع/تحصيل جزئي
                    // إذا أردت تقسيم القيد، أنشئ قيد جديد بالباقي وأغلق القديم
                    break;
                }
            }

            return $payment;
        });
    }
}
