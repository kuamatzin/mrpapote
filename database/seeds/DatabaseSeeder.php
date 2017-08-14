<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Ingredient::class, 12)->create();
        factory(App\Category::class, 4)->create()->each(function ($category) {
            for ($i=0; $i < 4; $i++) { 
                $subcategory = $category->subcategories()->save(factory(App\Subcategory::class)->make());
                $cont = random_int(2, 6);
                for ($f=0; $f < $cont; $f++) { 
                    $product = $subcategory->products()->save(factory(App\Product::class)->make());
                    //$creation = $product->creations()->save(factory(App\Creation::class)->make());
                    for ($g=0; $g < 3; $g++) {
                        $number_ingredients = random_int(2, 5);
                        $array_ingredients = range(1, 12);
                        shuffle($array_ingredients);
                        //$creation->ingredients()->attach(array_slice($array_ingredients, 0, $number_ingredients));
                        $product->ingredients()->attach(array_slice($array_ingredients, 0, $number_ingredients));
                    }
                }
            }
        });
    }
}
