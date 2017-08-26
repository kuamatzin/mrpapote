<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    public function index()
    {
        return Auth::user()->orders()->getByDate(Input::get('date'))->get();
    }

    public function show(Order $order)
    {
        $products = $order->order_products();
        return compact('products');
    }

    public function store(Request $request)
    {
        $products = $request->order_products;
        $order = Order::create(['name' => $request->name, 'total' => $request->total, 'user_id' => Auth::id()]);
        $order->relateOrderProducts($products);
    }

    public function update(Order $order, Request $request)
    {
        $products = $request->order_products;
        $order->update(['name' => $request->name, 'total' => $request->total]);
        $order->relateOrderProducts($products, true);
    }

    public function updatePayed(Order $order, Request $request)
    {
        $order->payed = !$request->payed;
        $order->save();
    }

    public function updateDelivered(Order $order, Request $request)
    {
        $order->delivered = !$request->delivered;
        $order->save();
    }

    public function destroy(Order $order)
    {
        $order->completeDelete();
    }
}
