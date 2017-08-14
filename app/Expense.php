<?php

namespace App;

use App\Supply;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * Get supply
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
