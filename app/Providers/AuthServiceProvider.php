<?php

namespace App\Providers;

use App\Category;
use App\Ingredient;
use App\Order;
use App\Policies\CategoryPolicy;
use App\Policies\IngredientPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SubcategoryPolicy;
use App\Product;
use App\Subcategory;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Subcategory::class => SubcategoryPolicy::class,
        Category::class => CategoryPolicy::class,
        Ingredient::class => IngredientPolicy::class,
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
