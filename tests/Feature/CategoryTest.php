<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CategoriesTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function an_unauthenticated_user_can_not_create_categories()
    {
        $response = $this->json('POST', '/categories', []);

        $response->assertStatus(401);
    }

    /** @test */
    function an_authenticated_user_can_create_categories()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs($user)->json('POST', '/categories', ['name' => 'Sally']);

        $response->assertStatus(201)->assertJson([
            'message' => 'Created',
        ]);
    }

    /** @test */
    function retrieve_user_categories()
    {
        $user = factory('App\User')->create();
        factory('App\Category')->create(['user_id' => $user->id]);
        factory('App\Category')->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->json('GET', 'categories');

        $this->assertCount(2, $response->original);

        foreach ($response->original as $category) {
            $this->assertInstanceOf('App\Category', $category);   
        }
    }
}
