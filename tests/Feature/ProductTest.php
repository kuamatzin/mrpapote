<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use App\Subcategory;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductTest extends TestCase
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
        $this->user->categories[0]->subcategories[0]->products()->save(factory('App\Product')->make(['subcategory_id' => $subcategory->id]));

        $this->second_user = factory('App\User')->create();
        $this->second_user->categories()->save($category = factory('App\Category')->make(['user_id' => $this->second_user->id]));
        $this->second_user->categories[0]->subcategories()->save($subcategory = factory('App\Subcategory')->make(['category_id' => $category->id]));
        $this->second_user->categories[0]->subcategories[0]->products()->save(factory('App\Product')->make(['subcategory_id' => $subcategory->id]));
    }

    /** @test */
    function unauthenticated_users_can_not_retrieve_products()
    {
        $response = $this->json('GET', '/products');

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated.',
        ]);;
    }

    /** @test */
    function authenticated_users_can_retrieve_products()
    {
        $this->user->categories[0]->subcategories[0]->products()->save(factory('App\Product')->make());
        $response = $this->actingAs($this->user)->json('GET', '/products');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $response->original);

        foreach ($response->original as $product) {
            $this->assertInstanceOf('App\Product', $product);
        }
    }

    /** @test */
    function users_who_own_a_subcategory_can_get_products_associated_whit_the_subcategory()
    {
        $response = $this->actingAs($this->user)->json('GET', '/products/findBySubcategory/' . $this->second_user->subcategories[0]->id);

        $response->assertStatus(403);

        $this->user->categories[0]->subcategories[0]->products()->save(factory('App\Product')->make());
        $this->user->categories[0]->subcategories[0]->products()->save(factory('App\Product')->make());

        $response = $this->actingAs($this->user)->json('GET', '/products/findBySubcategory/' . $this->user->subcategories[0]->id);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $response->original);

        foreach ($response->original as $product) {
            $this->assertInstanceOf('App\Product', $product);
        }
    }

    /** @test */
    function users_who_own_a_subcategory_can_store_products()
    {
        $product = factory('App\Product')->make(['subcategory_id' => $this->second_user->subcategories[0]->id]);

        $response = $this->actingAs($this->user)->json('POST', '/products', $product->toArray());

        $response->assertStatus(403);

        $product = factory('App\Product')->make(['subcategory_id' => $this->user->subcategories[0]->id])->toArray();
        
        unset($product['category_name']);

        $response = $this->actingAs($this->user)->json('POST', '/products', $product);

        $this->assertInstanceOf('App\Product', $response->original);
    }

    /** @test */
    function users_who_own_a_product_can_update_it()
    {
        $product = factory('App\Product')->make(['subcategory_id' => $this->second_user->subcategories[0]->id]);

        $response = $this->actingAs($this->user)->json('PUT', '/products/' . $this->second_user->products()->get()[0]->id, $product->toArray());

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->json('PUT', '/products/' . $this->user->products()->get()[0]->id, $product->toArray());

        $this->assertInstanceOf('App\Product', $response->original);
    }

    /** @test */
    function users_who_own_a_product_can_delete_it()
    {
        $response = $this->actingAs($this->user)->json('DELETE', '/products/' . $this->second_user->products()->get()[0]->id);

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->json('DELETE', '/products/' . $this->user->products()->get()[0]->id);

        $response->assertStatus(200)->assertJson([
            'message' => 'Deleted',
        ]);
    }

    /** @test */
    function users_who_own_a_product_can_retrieve_the_product_ingredients()
    {
        $response = $this->actingAs($this->user)->json('GET', '/products/' . $this->second_user->products()->get()[0]->id . '/ingredients');

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->json('GET', '/products/' . $this->user->products()->get()[0]->id . '/ingredients');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $response->original);

    }
}
