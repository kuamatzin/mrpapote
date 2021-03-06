<?php

use App\User;
use Illuminate\Http\Request;

Route::get('/', 'PageController@index');
Route::get('/mysales', 'PageController@orders');
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

//Expenses
Route::get('/expenses/getByMonth', 'ExpenseController@get');
Route::resource('/expenses', 'ExpenseController');

//Stats
Route::get('/statistics', 'StatsController@index');
Route::get('/statistics/product/{product}', 'StatsController@getByProduct');
Route::get('/statistics/subcategory/{subcategory}', 'StatsController@getBySubcategory');
Route::get('/statistics/subcategoryProducts/{subcategory}', 'StatsController@getBySubcategoryProducts');
Route::get('/statistics/generalStats', 'StatsController@generalStats');

Route::get('test', function(){
    dd(Auth::user()->hasAnActiveSubscription());
});

Route::get('user/invoice/{invoice}', function (Request $request, $invoiceId) {
    return $request->user()->downloadInvoice($invoiceId, [
        'vendor'  => 'Mi Changarro',
        'product' => 'Membresía',
    ]);
});


//Subscribe
Route::get('subscribe', 'SubscribeController@show');
Route::post('subscribe/{plan}', 'SubscribeController@subscribe');
Route::post('subscribe/swap/{plan}', 'SubscribeController@swapPlan');
Route::post('updateCard', 'SubscribeController@updateCard');
Route::delete('subscription', 'SubscribeController@cancelSubscription');

//Webhooks
Route::post('stripe/webhook','\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

//Social Login
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

Auth::routes();
