<?php

use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Subcategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'price' => random_int(10, 70),
        'active' => true,
        'personalizable' => false
    ];
});

$factory->define(App\Creation::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Ingredient::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'total' => random_int(30, 300),
        'delivered' => true,
        'payed' => true
        'created_at' => Carbon::today()
    ];
});
