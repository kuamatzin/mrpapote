<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('subscribe.show');    
    }

    public function subscribe($plan, Request $request)
    {
        if (Auth::user()->hasAnActiveSubscription()) {
            return response()->json(['message' => 'Already subscribed to an active plan'], 405);
        }
        else {
            Auth::user()->newSubscription($plan, $plan)->create($request->stripeToken, [
                'email' => $request->stripeEmail,
            ]);

            return response()->json(['message' => 'Subscribed'], 200);
        }
    }

    public function swapPlan($plan)
    {
        Auth::user()->subscription('silver')->swap($plan)->update(['name' => $plan]);

        return response()->json(['message' => 'Swaped Plan'], 200);
    }

    public function cancelSubscription()
    {
        $user->subscription('main')->cancel();
    }

    public function updateCard(Request $request)
    {
        Auth::user()->updateCard($request->stripeToken);

        return response()->json(['message' => 'Card Updated'], 200);
    }
}
