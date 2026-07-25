<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use Faker\Factory as Faker;

class DummyOrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper($faker->unique()->bothify('????-####')),
                'customer_name' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'payment_type' => $faker->randomElement(['cash', 'card', 'transfer']),
                'payment_status' => $faker->randomElement(['pending', 'paid', 'failed']),
                'delivery_type' => $faker->randomElement(['pickup', 'delivery']),
                'status' => $faker->randomElement(['In Progress', 'Completed']),
                'total_price' => 0,
            ]);

            $numItems = $faker->numberBetween(1, 4);
            for ($j = 0; $j < $numItems; $j++) {
                $quantity = $faker->numberBetween(1, 5);
                $unitPrice = $faker->randomFloat(2, 5, 100);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => ucfirst($faker->words(2, true)),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $quantity * $unitPrice,
                ]);
            }

            $order->load('orderItems')->recalculateTotal()->save();
        }
    }
}
