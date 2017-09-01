<?php

namespace App;

use App\Category;
use App\Ingredient;
use App\Order;
use App\SocialAccount;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get total orders in the month
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @param  Int $month
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTotalExpensesByMonth($query, $month)
    {
        return $query->whereMonth('buy_date', '=', $month)->sum('total');
    }

    public static function scopeExistsWithEmail($query, $email)
    {
        return $query->whereEmail($email);
    }
}
