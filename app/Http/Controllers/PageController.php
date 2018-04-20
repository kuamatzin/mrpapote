<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class PageController extends Controller
{

    /**
     * Create a new PageController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['orders', 'admin']);
    }

    public function index()
    {
        return view('page.home');
    }

    /**
     * Get orders
     * @return View
     */
    public function orders()
    {
        return view('page.orders');
    }

    /**
     * Admin view
     * @return View
     */
    public function admin()
    {
        return view('page.admin');
    }


    public function account()
    {
        return view('page.account');
    }
}
