<?php

namespace App;

use App\Creation;
use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    /**
     * Return the user of a order
     * @return  \App\User 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get creations
     * @return  \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function creations()
    {
        return $this->morphedByMany(Creation::class, 'orderable');
    }


    /**
     * Get the creations of the order (In order to get compatible element with th)e UI)
     * @return Array 
     */
    public function creations_products()
    {
          return $this->creations->map(function ($creation, $key) {
            $product = $creation->product;
            $product->creation_id = $creation->id;
            $product->quantity = 1;
            $product->ingredients = $creation->ingredients;
            $product->personalizable = true;
            return $product;
        });
    }

    /**
     * Get the creations and products of the order
     * @return Array
     */
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

    /**
     * Relate the creations and products for the order
     * @param  Array  $products
     * @param  boolean $update
     */
    public function relateOrderProducts($products, $update = false)
    {
        if ($update) {
            $this->emptyOrder();
        }
        foreach ($products as $key => $product) {
            if ($product['personalizable']) {
                //Make Creation::class
                $creation = Creation::create(['product_id' => $product['id']]);
                foreach ($product['ingredients'] as $key => $ingredient) {
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

    /**
     * Empty the order and delete from the database
     */
    public function completeDelete()
    {
        $this->emptyOrder();
        $this->delete();
    }

    /**
     * Empty the order
     */
    public function emptyOrder()
    {
        $this->creations()->delete();
        $this->creations()->detach();
        $this->products()->detach();
    }

    /**
     * Get orders by date
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  String $date  
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByDate($query, $date = null)
    {
        //If date is null we proceed to get the "today's" orders
        return $date ? $query->whereRaw('date(created_at) = ?', [$date]) : $query->where('created_at', '>=', Carbon::today()->format('Y-m-d'));
    }

    /**
     * Get total orders in the month
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @param  Int $month
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTotalByMonth($query, $month)
    {
        return $query->whereMonth('created_at', '=', $month);
    }

    /**
     * Get total orders in the month
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @param  Int $month
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTotalRevenueByMonth($query, $month)
    {
        return $query->whereMonth('created_at', '=', $month);
    }
}
