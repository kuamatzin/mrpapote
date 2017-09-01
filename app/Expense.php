<?php

namespace App;

use App\Supply;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    protected $dates = ['buy_date'];

    /**
     * Get the user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get supply
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    /**
     * Get the total of all expenses in a month
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @param  Int $month
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTotalExpensesByMonth($query, $month)
    {
        return $query->whereMonth('buy_date', '=', $month)->sum('total');
    }
}
