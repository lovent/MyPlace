<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Booking::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(['1','2']),
        'created_at' => now(),
    ];
});
