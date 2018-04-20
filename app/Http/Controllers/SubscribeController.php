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
    
    /**
     * Return the view for subscriptions
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('subscribe.show');    
    }

    /**
     * Suscribe the user to a plan
     *
     * @param String $plan
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscribe($plan, Request $request)
    {
        if (Auth::user()->hasAnActiveSubscription()) {
            return response()->json(['message' => 'Already subscribed to an active plan'], 405);
        }
        else if(Auth::user()->hasAGracePeriodPlan()){
            Auth::user()->resumeSubscription($plan);

            return response()->json(['message' => 'Resumend Subscription'], 200);
        }
        else {
            Auth::user()->subscribeToPlan($plan, $request);

            return response()->json(['message' => 'Subscribed'], 200);
        }
    }

    /**
     * Swap the current user plan to the given 
     *
     * @param String $plan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function swapPlan($plan)
    {
        Auth::user()->swapToPlan($plan);

        return response()->json(['message' => 'Swaped Plan'], 200);
    }

    /**
     * Cancel the current subscription
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelSubscription()
    {
        Auth::user()->cancelActiveSubscription();

        return response()->json(['message' => 'Subscription Canceled'], 200);
    }

    /**
     * Update the current card on Stripe
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateCard(Request $request)
    {
        Auth::user()->updateCard($request->stripeToken);

        return response()->json(['message' => 'Card Updated'], 200);
    }
}
