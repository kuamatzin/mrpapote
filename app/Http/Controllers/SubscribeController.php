<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller
{
    public function subscribe($plan, Request $request)
    {
        Auth::user()->newSubscription('gold', 'gold')->create($request->stripeToken);
    }
}
