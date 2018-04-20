<?php

namespace App;

use App\Category;
use App\Ingredient;
use App\Order;
use App\SocialAccount;
use App\Subscription;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Services\Billable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Billable;
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

    /**
     * Get the user orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the user ingredients
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * Get the user socialAccounts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the user expenses
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the user if has the email provided
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Int $email
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeExistsWithEmail($query, $email)
    {
        return $query->whereEmail($email);
    }

    /**
     * Get all the products associated with the user account
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function products()
    {
        return Product::join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->where('categories.user_id', $this->id)
                ->select('products.*');
    }


    /**
     * Get the subcategories associated with the user account
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function subcategories()
    {
        return $this->hasManyThrough(Subcategory::class, Category::class);
    }
}
