<?php

namespace Tests\Feature;

use App\Category;
use App\User;
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
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', '/categories', ['name' => 'Sally']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Created',
            ]);
    }

    /** @test */
    function retrieve_user_categories()
    {
        $this->actingAs(factory(User::class)->create())->json('POST', '/categories', ['name' => 'Sally']);

        $this->json('POST', '/categories', ['name' => 'Sally 2']);

        $first_user_categories = $this->json('GET', 'categories');


        $this->json('POST', 'logout');

        $this->actingAs(factory(User::class)->create())->json('POST', '/categories', ['name' => 'Carlos']);

        $second_user_categories = $this->json('GET', 'categories');

        $this->assertCount(2, $first_user_categories->original);

        $this->assertCount(1, $second_user_categories->original);
    }
}
