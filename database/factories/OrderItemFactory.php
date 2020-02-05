<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\OrderItem;
use Faker\Generator as Faker;

$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'notes' => $faker->sentence,
        'quantity' => rand(1, 100),
        'price' => rand(100, 1000000),
        'order_id' => factory(\App\Order::class),
    ];
});
