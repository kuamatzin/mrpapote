<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $guarded = [];
    
    /**
     * Get category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get products
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function stats($month)
    {  
        $products = $this->products;

        $orders = 0; $revenue = 0;
        foreach ($products as $product) {
            $product_stat = $product->stats($month);
            $orders = $orders + $num_orders = $product_stat->count();
            $revenue = $revenue + $product->price * $num_orders;
        }

        return ['orders' => $orders, 'revenue' => $revenue];
    }
}
