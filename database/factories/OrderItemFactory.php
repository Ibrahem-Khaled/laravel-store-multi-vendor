<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product   = Product::factory()->create(); // نحتاج تاجر من الـ Brand->user
        $qty       = $this->faker->numberBetween(1, 4);
        $unit      = $product->price;
        $lineTotal = round($qty * $unit, 2);
        $rate      = $this->faker->randomElement([0.10, 0.12, 0.15]);
        $commission = round($lineTotal * $rate, 2);
        $payout    = round($lineTotal - $commission, 2);

        return [
            'order_id'          => Order::factory(),
            'product_id'        => $product->id,
            'merchant_id'       => $product->brand->user_id,
            'quantity'          => $qty,
            'unit_price'        => $unit,
            'commission_rate'   => $rate,
            'commission_amount' => $commission,
            'payout_amount'     => $payout,
        ];
    }
}
