<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function orders()
    {
        $orders = Order::all();
        return view('page.orders', compact('orders'));
    }

    public function admin()
    {
        return view('page.admin');
    }
}
