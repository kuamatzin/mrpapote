<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Get user orders by Da1te
     * @return  Illuminate\Database\Eloquent\Collection;
     */
    public function index()
    {
        return Auth::user()->orders()->getByDate(Input::get('date'))->get();
    }

    /**
     * Get order
     * @param  Order  $order 
     * @return App\Order        
     
    public function show(Order $order)
    {
        $products = $order->order_products();
        return compact('products');
    }

    */

    /**
     * Store new Order
     * @param  Request $request 
     */
    public function store(Request $request)
    {
        $products = $request->order_products;
        $order = Order::create(['name' => $request->name, 'total' => $request->total, 'user_id' => Auth::id()]);
        $order->relateOrderProducts($products);

        return response()->json([
            'message' => 'Created'
        ], 201);
    }

    /**
     * Update order
     * @param  Order   $order   
     * @param  Request $request 
     */
    public function update(Order $order, Request $request)
    {
        $this->authorize($order);

        $products = $request->order_products;
        $order->update(['name' => $request->name, 'total' => $request->total]);
        $order->relateOrderProducts($products, true);

        return response()->json([
            'message' => 'Updated'
        ], 200);
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
