<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function orders()
    {
        $products = Product::all();
        $orders = Order::with('products')->get();
        dd($orders[0]->products->where('id', 12));
        $stats = [];
        foreach ($products as $key => $product) {
            $stats[$product->name] = $orders->map(function($order, $key) use ($product){
                return $order->products()->where('product_id', $product->id);
            });
        }
        dd($stats);
    }
}
