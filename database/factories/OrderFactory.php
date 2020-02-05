<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'destination' => $faker->words(2, true),
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'person_id' => factory(\App\Person::class),
    ];
});
