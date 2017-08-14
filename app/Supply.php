<?php

namespace App;

use App\Expense;
use App\Ingredient;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    
    /**
     * Get ingredients
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get expense
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expense()
    {
        return $this->hasOne(Expense::class);
    }
}
