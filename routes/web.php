<?php

use App\Order;
use Carbon\Carbon;

Route::get('/', 'PageController@orders');
Route::get('/admin', 'PageController@admin');

//Products
Route::get('/products/{product}/ingredients', 'ProductController@getProductIngredients');
Route::get('/products/findBySubcategory/{subcategory}', 'ProductController@getBySubcategory');
Route::put('/products/{product}/ingredients', 'ProductController@updateIngredients');
Route::resource('/products', 'ProductController');

//Ingredients
Route::resource('/ingredients', 'IngredientsController');

//Categories
Route::resource('/categories', 'CategoryController');

//Subcategories
Route::get('/subcategories/findByCategory/{category}', 'SubcategoryController@getByCategory');
Route::resource('/subcategories', 'SubcategoryController');

//Orders
Route::put('/orders/updatePayed/{order}', 'OrderController@updatePayed');
Route::put('/orders/updateDelivered/{order}', 'OrderController@updateDelivered');
Route::resource('/orders', 'OrderController');

Route::get('/test', function(){
    $orders = Order::where('created_at', '>=', Carbon::today())->get();
    dd($orders);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
