<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Phone;
use Faker\Generator as Faker;

$factory->define(Phone::class, function (Faker $faker) {
    return [
        'number' => preg_replace('/\\D/', '', $faker->phoneNumber),
        'person_id' => factory(\App\Person::class),
    ];
});
