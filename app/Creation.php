<?php

namespace App;

use App\Ingredient;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Creation extends Model
{

    protected $guarded = [];
    /**
     * Get product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get Ingredients
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
