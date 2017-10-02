<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubcategoryTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $second_user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->user->categories()->save($category = factory('App\Category')->make(['user_id' => $this->user->id]));
        $this->user->categories[0]->subcategories()->save($subcategory = factory('App\Subcategory')->make(['category_id' => $category->id]));
        $this->user->categories[0]->subcategories()->save($subcategory = factory('App\Subcategory')->make(['category_id' => $category->id]));

        $this->second_user = factory('App\User')->create();
        $this->second_user->categories()->save($category = factory('App\Category')->make(['user_id' => $this->second_user->id]));
        $this->second_user->categories[0]->subcategories()->save($subcategory = factory('App\Subcategory')->make(['category_id' => $category->id]));
    }

    /** @test */
    function authenticated_users_can_get_subcategories_from_category()
    {
        $response = $this->actingAs($this->user)->json('GET', '/subcategories/findByCategory/' . $this->user->categories[0]->id);

        $this->assertCount(2, $response->original);

        foreach ($response->original as $subcategory) {
            $this->assertInstanceOf('App\Subcategory', $subcategory);
        }
    }

    /** @test */
    function unauthenticated_users_can_not_get_subcategories()
    {
        $response = $this->json('GET', '/subcategories/findByCategory/' . $this->user->categories[0]->id);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated.',
        ]);
    }

    /** @test */
    function users_who_not_belongs_a_categorhy_wont_retrieve_subcategories()
    {
        $response = $this->actingAs($this->user)->json('GET', '/subcategories/findByCategory/' . $this->second_user->categories[0]->id);

        $response->assertStatus(403);
    }

    /** @test */
    function store_subcategory()
    {
        $subcategory = factory('App\Subcategory')->make(['category_id' => $this->user->categories[0]->id]);

        $response = $this->actingAs($this->user)->json('POST', 'subcategories', $subcategory->toArray());

        $response->assertStatus(201)->assertJson([
            'message' => 'Created',
        ]);
    }

    /** @test */
    function update_subcategory()
    {
        $subcategory = factory('App\Subcategory')->create(['category_id' => $this->user->categories[0]->id]);

        $subcategory->name = 'Edited';

        $response = $this->actingAs($this->user)->json('PUT', 'subcategories/' . $subcategory->id, $subcategory->toArray());

        $this->assertEquals($this->user->categories[0]->subcategories[2]->name, 'Edited');

        $response->assertStatus(200)->assertJson([
            'message' => 'OK',
        ]);
    }
}
