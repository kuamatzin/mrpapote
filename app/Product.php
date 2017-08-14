<?php

namespace App;

use App\Creation;
use App\Ingredient;
use App\Subcategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = [];

    protected $appends = ['category_name'];
    /**
     * Get subcategories
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Get ingredients
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * Get creations
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */ 
    public function creations()
    {
        return $this->hasMany(Creation::class);
    }

    /**
     * Get the category name of a product
     * @return String 
     */
    public function getCategoryNameAttribute()
    {
        return $this->subcategory->category->name;
    }

    /**
     * Syn ingredients allowing duplicate ingredients in the same product
     * @param  array $ingredients 
     */
    public function syncIngredients($ingredients)
    {
        $this->ingredients()->detach();
        $ingredients_ids = array_pluck($ingredients, 'id');
        $duplicates = array_count_values($ingredients_ids);
        foreach ($duplicates as $key => $times) {
            for ($i=0; $i < $times; $i++) { 
                $this->ingredients()->attach($key);
            }
        }
    }
}
