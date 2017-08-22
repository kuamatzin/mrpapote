<?php

use App\User;

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

//Stats
Route::get('/stats/orders', 'StatsController@orders');

Route::get('/test', function(){
    $user = User::existsWithEmail('kuamatzi2n@gmail.com')->first();
    dd($user);
});

//Social Login
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

Auth::routes();

Auth::routes();
