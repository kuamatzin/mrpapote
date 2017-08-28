<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create()->each(/**
         * @param $user
         */
            function ($user) {
            for ($i = 0; $i < 12; $i++) {
                $user->ingredients()->save(factory(App\Ingredient::class)->make());
            }
            $categories = array();
            for ($i = 0; $i < random_int(2, 3); $i++) {
                array_push($categories, $user->categories()->save(factory(App\Category::class)->make()));
            }
            foreach ($categories as $category) {
                for ($i = 0; $i < random_int(2, 4); $i++) {
                    $subcategory = $category->subcategories()->save(factory(App\Subcategory::class)->make());
                    $cont = 6;
                    for ($f = 0; $f < $cont; $f++) {
                        $product = $subcategory->products()->save(factory(App\Product::class)->make());
                        $creation = $product->creations()->save(factory(App\Creation::class)->make());
                        for ($g = 0; $g < 3; $g++) {
                            $number_ingredients = random_int(2, 5);
                            $array_ingredients = range(1, 12);
                            shuffle($array_ingredients);
                            $creation->ingredients()->attach(array_slice($array_ingredients, 0, $number_ingredients));
                            $product->ingredients()->attach(array_slice($array_ingredients, 0, $number_ingredients));
                        }
                    }
                }
            }

            //Create the orders
            $faker = Faker::create();
            for ($month = 5; $month < 9; $month ++){
                for ($day = 0; $day < 29; $day ++) {
                    for($numero_ventas = 0; $numero_ventas < random_int(5, 22); $numero_ventas ++){
                        $order = $user->orders()->save(factory(App\Order::class)->make([
                            'name' => $faker->name,
                            'total' => random_int(30, 300),
                            'delivered' => true,
                            'payed' => true,
                            'created_at' => Carbon::createFromDate(2017, $month, $day, 'America/Mexico_City')
                        ]));
                        $number_products = random_int(1, 8);
                        $array_products = range(1, 6);
                        $order->products()->attach(array_slice($array_products, 0, $number_products));
                    }
                }
            }
        });
    }
}
