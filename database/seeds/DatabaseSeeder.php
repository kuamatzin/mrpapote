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
        //Create User
        factory(App\User::class)->create()->each(function ($user) {
            //Create Ingredientes
            for ($i = 0; $i < 12; $i++) {
                $user->ingredients()->save(factory(App\Ingredient::class)->make());
            }
            //Create Categories
            $categories = array();
            for ($i = 0; $i < random_int(2, 3); $i++) {
                array_push($categories, $user->categories()->save(factory(App\Category::class)->make()));
            }
            foreach ($categories as $category) {
                for ($i = 0; $i < random_int(2, 4); $i++) {
                    //Create subcategories
                    $subcategory = $category->subcategories()->save(factory(App\Subcategory::class)->make());
                    $cont = 6;
                    //Create products and creations
                    for ($f = 0; $f < $cont; $f++) {
                        $product = $subcategory->products()->save(factory(App\Product::class)->make());
                        $creation = $product->creations()->save(factory(App\Creation::class)->make());
                        //Attach ingredients to products and creations
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

            $faker = Faker::create();
            for ($month = 1; $month < 10; $month ++){
                for ($day = 1; $day < 29; $day ++) {
                    //Create expenses
                    $expense = $user->expenses()->save(factory(App\Expense::class)->make([
                            'description' => $faker->name,
                            'total' => random_int(300, 1000),
                            'buy_date' => Carbon::createFromDate(2017, $month, $day)
                        ]));
                    for($numero_ventas = 0; $numero_ventas < random_int(5, 22); $numero_ventas ++){
                        //Create orders
                        $order = $user->orders()->save(factory(App\Order::class)->make([
                            'name' => $faker->name,
                            'total' => random_int(30, 300),
                            'delivered' => true,
                            'payed' => true,
                            'created_at' => Carbon::createFromDate(2017, $month, $day, 'America/Mexico_City')
                        ]));

                        $number_products = random_int(1, 8);
                        $array_products = range(1, 30);
                        shuffle($array_products);
                        $order->products()->attach(array_slice($array_products, 0, $number_products));
                    }
                }
            }
        });
    }
}
