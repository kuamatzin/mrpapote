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
        factory(App\User::class, 1)->create()->each(function ($user) {
            $this->createIngredients($user);

            $categories = $this->createCategories($user);

            $this->createSubcategories($categories);

            $this->createExpensesAndOrdersSeveralMonths($user);

            $this->updateFirstUser($user);
        });
    }

    public function createIngredients($user)
    {
        for ($i = 0; $i < 12; $i++) {
            factory(App\Ingredient::class)->create(['user_id' => $user->id]);
        }
    }

    public function createCategories($user)
    {
        $categories = array();
        for ($i = 0; $i < random_int(2, 3); $i++) {
            $category = factory(App\Category::class)->create(['user_id' => $user->id]);
            array_push($categories, $category);
        }   
        return $categories;
    }

    public function createSubcategories($categories)
    {
        foreach ($categories as $category) {
            for ($i = 0; $i < random_int(2, 4); $i++) {
                //Create subcategories
                $subcategory = factory(App\Subcategory::class)->create(['category_id' => $category->id]);

                //Create products and creations
                $this->createProductsAndCreations($subcategory);
            }
        }
    }

    public function createProductsAndCreations($subcategory)
    {
        for ($f = 0; $f < 6; $f++) {
            $product = factory(App\Product::class)->create(['subcategory_id' => $subcategory->id]);

            $creation = factory(App\Creation::class)->create(['product_id' => $product->id]);

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

    public function createExpensesAndOrdersSeveralMonths($user)
    {
        $faker = Faker::create();
        for ($month = 1; $month < 10; $month ++){
            for ($day = 1; $day < 29; $day ++) {
                //Create expenses
                $expense = factory(App\Expense::class)->create(['user_id' => $user->id, 'buy_date' => Carbon::createFromDate(2017, $month, $day)]);

                $this->createOders($user, $month, $day);
            }
        }
    }

    public function createOders($user, $month, $day)
    {
        for($numero_ventas = 0; $numero_ventas < random_int(5, 22); $numero_ventas ++){
            //Create orders
            $order = factory(App\Order::class)->create([
                'user_id' => $user->id,
                'created_at' => Carbon::createFromDate(2017, $month, $day, 'America/Mexico_City')
            ]);

            $number_products = random_int(1, 8);
            $array_products = range(1, 30);
            shuffle($array_products);
            $order->products()->attach(array_slice($array_products, 0, $number_products));
        }
    }

    public function updateFirstUser($user)
    {
        if ($user->id == 1) {
            $user->update([
                'name' => 'Carlos Cuamatzin',
                'email' => 'kuamatzin@gmail.com'
            ]);
        }
    }
}
