<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Order::class, 100)->create(['person_id' => rand(1, 100)])->each(function ($order) {
            factory(\App\OrderItem::class, rand(1, 5))->create(['order_id' => $order->id]);
        });
    }
}
