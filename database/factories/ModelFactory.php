<?php

use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'name' => $faker->name
    ];
});

$factory->define(App\Subcategory::class, function (Faker\Generator $faker) {
    return [
        'category_id' => function () {
            return factory('App\Category')->create()->id;
        },
        'name' => $faker->name
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'subcategory_id' => function () {
            return factory('App\Subcategory')->create()->id;
        },
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
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'name' => $faker->name
    ];
});


$factory->define(App\Expense::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'description' => $faker->name,
        'total' => random_int(300, 1000)
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'name' => $faker->name,
        'total' => random_int(30, 300),
        'delivered' => false,
        'payed' => false,
        'created_at' => Carbon::today()
    ];
});
