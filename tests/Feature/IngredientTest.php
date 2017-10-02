<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IngredientTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_can_not_retrieve_ingredients()
    {
        $response = $this->json('GET', '/ingredients');

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated.',
        ]);;
    }

    /** @test */
    function authenticated_users_can_retrieve_ingredients()
    {
        $user = factory('App\User')->create();

        $user->ingredients()->save(factory('App\Ingredient')->make());
        $user->ingredients()->save(factory('App\Ingredient')->make());

        $response = $this->actingAs($user)->json('GET', '/ingredients');

        $this->assertCount(2, $response->original);

        foreach ($response->original as $ingredient) {
            $this->assertInstanceOf('App\Ingredient', $ingredient);
        }
    }


    /** @test */
    function authenticated_users_can_store_ingredients()
    {
        $user = factory('App\User')->create();
        
        $ingredient = factory('App\Ingredient')->make();

        $response = $this->json('POST', 'ingredients', $ingredient->toArray());

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated.',
        ]);;

        $response = $this->actingAs($user)->json('POST', 'ingredients', $ingredient->toArray());

        $response->assertStatus(201)->assertJson([
            'message' => 'Created',
        ]);
    }

    /** @test */
    function users_who_own_an_ingredient_can_update_it()
    {
        $user = factory('App\User')->create();
        $second_user = factory('App\User')->create();

        $user->ingredients()->save(factory('App\Ingredient')->make());
        $second_user->ingredients()->save(factory('App\Ingredient')->make());

        $response = $this->actingAs($user)->json('PUT', 'ingredients/' . $second_user->ingredients[0]->id, ['name' => 'Edited']);

        $response->assertStatus(403);

        $response = $this->actingAs($user)->json('PUT', 'ingredients/' . $user->ingredients[0]->id, ['name' => 'Edited']);

        $response->assertStatus(200)->assertJson([
            'message' => 'OK',
        ]);
    }

    /** @test */
    function users_who_own_an_ingredient_can_delete_it()
    {
        $user = factory('App\User')->create();
        $second_user = factory('App\User')->create();

        $user->ingredients()->save(factory('App\Ingredient')->make());
        $second_user->ingredients()->save(factory('App\Ingredient')->make());

        $response = $this->actingAs($user)->json('DELETE', 'ingredients/' . $second_user->ingredients[0]->id);

        $response->assertStatus(403);

        $response = $this->actingAs($user)->json('DELETE', 'ingredients/' . $user->ingredients[0]->id);

        $response->assertStatus(200)->assertJson([
            'message' => 'Deleted',
        ]);
    }
}
