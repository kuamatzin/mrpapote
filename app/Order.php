<?php

namespace App;

use App\Creation;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    /**
     * Get creations
     * @return  \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function creations()
    {
        return $this->morphedByMany(Creation::class, 'orderable');
    }


    public function creations_products()
    {
          return $this->creations->map(function ($creation, $key) {
            $product = $creation->product;
            $product->creation_id = $creation->id;
            $product->quantity = 1;
            $product->product_ingredients = $creation->ingredients;
            $product->personalizable = true;
            return $product;
        });
    }

    public function order_products()
    {
        $products = array();
        foreach ($this->products->groupBy('id') as $key => $order_products) {
            $product = $order_products->first();
            $product->quantity = $order_products->count();
            $product->personalizable = false;
            array_push($products, $product);
        }
        $creations = $this->creations_products()->toArray();

        return array_merge($products, $creations);
    }

    public function relateOrderProducts($products, $update = false)
    {
        if ($update) {
            $this->creations()->delete();
            $this->creations()->detach();
            $this->products()->detach();
        }
        foreach ($products as $key => $product) {
            if ($product['personalizable']) {
                //Make Creation::class
                $creation = Creation::create(['product_id' => $product['id']]);
                foreach ($product['product_ingredients'] as $key => $ingredient) {
                    $creation->ingredients()->attach($ingredient['id']);
                }
                //Add creation to the order
                $this->creations()->attach($creation->id);
            }
            else {
                //Add Product to the Order
                //Adding same product in the order in case
                for ($i=0; $i < $product['quantity']; $i++) { 
                    $this->products()->attach($product['id']);
                }
            }
        }
    }

    /**
     * Get products
     * @return  \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products()
    {
        return $this->morphedByMany(Product::class, 'orderable');
    }
}
