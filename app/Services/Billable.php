<?php

namespace App\Services;

use Laravel\Cashier\Billable as UserBillable;
use App\Services\Plan;

trait Billable
{
    use UserBillable;

    /**
     * Determine if the user has an active subscription
     * @return boolean
     */
    public function hasAnActiveSubscription()
    {
        foreach (Plan::getPlans() as $key => $plan) {
            if ($this->subscribed($plan['name'])) {
                if ($this->subscription($plan['name'])->cancelled() == false) {
                    //Esta suscripto a plan y además no esta en status de cancelado
                    //es decir no esta en grace period
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Determine if the user has an subscription on grace period
     *
     * @return boolean
     */
    public function hasAGracePeriodPlan()
    {
        foreach (Plan::getPlans() as $key => $plan) {
            if ($this->subscribed($plan['name'])) {
                if ($this->subscription($plan['name'])->onGracePeriod()) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Subscribe a user to a plan
     *
     * @param String $plan
     * @param Request $request
     * @return void
     */
    public function subscribeToPlan($plan, $request)
    {
        $this->newSubscription($plan, $plan)->create($request->stripeToken, [
                        'email' => $request->stripeEmail,
                    ]);
    }

    /**
     * Resume Subscription
     *
     * @param String $plan
     * @return void
     */
    public function resumeSubscription($plan)
    {
        $active_subcription_name = $this->activeSubscriptionName();
        $this->subscription($active_subcription_name)->resume();

        if ($active_subcription_name != $plan) {
            $this->swapToPlan($plan);
        }
    }

    /**
     * Get the active user subscription, includes plan with Grace Periods
     *
     * @return String
     */
    public function activeSubscriptionName()
    {
        foreach (Plan::getPlans() as $key => $plan) {
            if ($this->subscribed($plan['name'])) {
                return $plan['name'];
            }
        }
        return false;
    }

    /**
     * If the user downgrande the plan, would have 2 active plans, 1 on grace period
     * and 1 active plan, this method returns only the last one
     *
     * @return String
     */
    public function activeSubscriptionNameWithoutGracePeriods()
    {
        foreach (Plan::getPlans() as $key => $plan) {
            if ($this->subscribed($plan['name'])) {
                if ($this->subscription($plan['name'])->cancelled() == false) {
                    return $plan['name'];
                }
            }
        }
        return false;
    }

    /**
     * Cancel the current user subscription
     *
     * @return void
     */
    public function cancelActiveSubscription()
    {
        $this->subscription($this->activeSubscriptionName())->cancel();
    }


    /**
     * Swap the user plan
     *
     * @param String $plan
     * @return void
     */
    public function swapToPlan($plan)
    {
        $this->subscription($this->activeSubscriptionNameWithoutGracePeriods())->swap($plan)->update(['name' => $plan]);
    }
}
