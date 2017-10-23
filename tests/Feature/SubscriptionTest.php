<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $stripeToken;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory('App\User')->create();

        $this->stripeToken = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => 2022,
                'cvc' => 111
            ],

            'email' => $this->user->email
        ], ['api_key' => getenv('STRIPE_KEY')]);
    }

    /** @test */
    function unauthenticated_users_can_not_subscribe()
    {
        $response = $this->json('POST', 'subscribe/{plan}', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated.',
        ]);
    }

    /** @test */
    function a_user_can_subscribe_to_silver_plan()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/silver', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Subscribed',
        ]);

        $this->assertTrue($this->user->fresh()->subscribed('silver'));
    }

    /** @test */
    function a_user_can_subscribe_to_gold_plan()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Subscribed',
        ]);

        $this->assertTrue($this->user->fresh()->subscribed('gold'));
    }

    /** @test */
    function user_can_swap_from_silver_plan_to_gold_plan()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/silver', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->assertTrue($this->user->fresh()->subscribed('silver'));

        $response = $this->actingAs($this->user->fresh())->json('POST', 'subscribe/swap/gold');

        $response->assertStatus(200)->assertJson([
            'message' => 'Swaped Plan',
        ]);

        $this->assertTrue($this->user->fresh()->subscribed('gold'));
    }

    /** @test */
    function a_user_only_can_have_one_active_subscription()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/silver', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response = $this->actingAs($this->user->fresh())->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->assertTrue($this->user->fresh()->subscribed('silver'));
        $response->assertStatus(405)->assertJson([
            'message' => 'Already subscribed to an active plan',
        ]);
        $this->assertFalse($this->user->fresh()->subscribed('gold'));
    }


    /** @test */
    function a_user_can_cancel_the_subscription()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->user->fresh()->subscription('gold')->cancel();

        $this->assertTrue($this->user->fresh()->subscription('gold')->onGracePeriod());
    }

    /** @test */
    function user_who_cancel_their_account_can_resume_it()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->user->fresh()->subscription('gold')->cancel();

        $this->user->fresh()->subscription('gold')->resume();

        $this->assertTrue($this->user->fresh()->subscribed('gold'));
    }

    /** @test */
    function users_who_one_time_had_a_subscription_can_subscribe_again()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->user->fresh()->subscription('gold')->cancelNow();

        $response = $this->actingAs($this->user->fresh())->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Subscribed',
        ]);

        $this->assertTrue($this->user->fresh()->subscribed('gold'));
    }

    /** @test */
    function a_user_can_update_his_credit_card()
    {
        $this->actingAs($this->user)->json('POST', 'subscribe/silver', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $this->assertEquals('4242', $this->user->fresh()->card_last_four);

        $newStripeToken = \Stripe\Token::create([
            'card' => [
                'number' => '5555555555554444',
                'exp_month' => 2,
                'exp_year' => 2023,
                'cvc' => 111
            ],
            'email' => $this->user->email
        ], ['api_key' => getenv('STRIPE_KEY')]);


        $response = $this->actingAs($this->user->fresh())->json('POST', 'updateCard', ['stripeToken' => $newStripeToken->id]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Card Updated',
        ]);

        $this->assertEquals('4444', $this->user->fresh()->card_last_four);

    }

}
