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
     * Get the order details, all the products and creations associated with the order
     * @param  Order  $order 
     * @return App\Order        
     */

    public function show(Order $order)
    {
        $products = $order->order_products();
        return compact('products');
    }

    /**
     * Store new Order
     * @param  Request $request 
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_products.*.id' => 'required'
        ]);

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

        $this->validate($request, [
            'order_products.*.id' => 'required'
        ]);

        $products = $request->order_products;
        $order->update(['name' => $request->name, 'total' => $request->total]);
        $order->relateOrderProducts($products, true);

        return response()->json([
            'message' => 'Updated'
        ], 200);
    }

    /**
     * Update if the order has been payed
     * @param  Order   $order   
     * @param  Request $request 
     * @return
     */
    public function updatePayed(Order $order, Request $request)
    {
        $this->authorize('update', $order);

        $order->payed = !$request->payed;
        $order->save();

        return response()->json([
            'message' => 'Updated'
        ], 200);
    }

    /**
     * Update if the order has been delivered
     * @param  Order   $order   
     * @param  Request $request 
     * @return            
     */
    public function updateDelivered(Order $order, Request $request)
    {
        $this->authorize('update', $order);

        $order->delivered = !$request->delivered;
        $order->save();

        return response()->json([
            'message' => 'Updated'
        ], 200);
    }

    /**
     * Delete a order
     * @param  Order  $order 
     * @return         
     */
    public function destroy(Order $order)
    {
        $this->authorize($order);

        $order->completeDelete();

        return response()->json([
            'message' => 'Deleted'
        ], 200);
    }
}
