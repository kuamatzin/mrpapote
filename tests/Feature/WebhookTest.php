<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebhookTest extends TestCase
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
    function when_a_subscription_ends_it_should_deactivate_the_user_plan()
    {
        $response = $this->actingAs($this->user)->json('POST', 'subscribe/gold', ['stripeToken' => $this->stripeToken->id, 'stripeEmail' => $this->stripeToken->email]);

        $response = $this->json('POST', 'stripe/webhook', [
            'type' =>  'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'id' => $this->user->fresh()->subscriptions[0]->stripe_id,
                    'customer' => $this->user->fresh()->stripe_id,
                ]
            ]
        ]);

        $response->assertStatus(200);

        $this->assertFalse($this->user->fresh()->subscribed('gold'));
    }


    
}
