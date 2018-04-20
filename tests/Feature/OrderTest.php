<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function get_orders_by_date()
    {   
        $user = factory('App\User')->create();
        $order = factory('App\Order', 5)->create([
            'user_id' => $user->id, 
            'created_at' => Carbon::createFromDate(2017, 1, 1, 'America/Mexico_City')
        ]);
        $order = factory('App\Order', 7)->create([
            'user_id' => $user->id, 
            'created_at' => Carbon::createFromDate(2017, 1, 2, 'America/Mexico_City')
        ]);

        $response = $this->actingAs($user)->json('GET', '/orders?date=' . '2017-01-01');

        $this->assertCount(5, $response->original);

        foreach ($response->original as $order) {
            $this->assertInstanceOf('App\Order', $order);
        }
    }


    /** @test */
    function authenticated_users_can_store_orders()
    {
        $subcategory = factory('App\Subcategory')->create();
        $user = $subcategory->category->user;

        $order_products = factory('App\Product', 5)->create(['subcategory_id' => $subcategory->id])->each(function($product){
            $product['quantity'] = random_int(1, 4);
        })->toArray();

        $data = factory('App\Order')->make()->toArray();
        unset($data['user_id']);
        $data['order_products'] = $order_products;
        
        $response = $this->actingAs($user)->json('POST', '/orders', $data);
        
        $response->assertStatus(201)->assertJson([
            'message' => 'Created'
        ]);

        $this->assertCount(5, $user->orders[0]->order_products());
    }

    /** @test */
    function users_who_own_a_order_can_update_it()
    {
        $subcategory = factory('App\Subcategory')->create();
        $user = $subcategory->category->user;

        $second_user = factory('App\User')->create();

        $order = $this->createOrderWithProducts($user, $subcategory, 5);

        $this->assertCount(5, $user->orders[0]->order_products());
        
        $update_order_products = factory('App\Product', 2)->create(['subcategory_id' => $subcategory->id])->each(function($product){
            $product['quantity'] = random_int(1, 4);
        })->toArray();

        $updated_data['name'] = $user->orders[0]->name;
        $updated_data['total'] = $user->orders[0]->total;
        $updated_data['order_products'] = $update_order_products;

        $response = $this->actingAs($second_user)->json('PUT', '/orders/' . $user->orders[0]->id, $updated_data);
        
        $response->assertStatus(403);

        $response = $this->actingAs($user)->json('PUT', '/orders/' . $user->orders[0]->id, $updated_data);

        $response->assertStatus(200)->assertJson([
            'message' => 'Updated'
        ]);

        $this->assertCount(2, $user->orders[0]->fresh()->order_products());
    }


    /** @test */
    function users_who_own_a_order_can_update_the_delevered_attribute()
    {
        $order = factory('App\Order')->create();

        $this->assertFalse($order->delivered);

        $response = $this->actingAs($order->user)->json('PUT', '/orders/updateDelivered/' . $order->id, ['delivered' => false]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Updated'
        ]);

        $this->assertEquals($order->fresh()->delivered, 1);        
    }


    /** @test */
    function users_who_own_a_order_can_update_the_payed_attribute()
    {
        $order = factory('App\Order')->create();

        $this->assertFalse($order->payed);

        $response = $this->actingAs($order->user)->json('PUT', '/orders/updatePayed/' . $order->id, ['payed' => false]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Updated'
        ]);
        
        $this->assertEquals($order->fresh()->payed, 1); 
    }

    /** @test */
    function users_who_own_a_order_can_delete_it()
    {
        $order = factory('App\Order')->create();

        $response = $this->actingAs($order->user)->json('DELETE', '/orders/' . $order->id);

        $response->assertStatus(200)->assertJson([
            'message' => 'Deleted',
        ]);
    }


    public function createOrderWithProducts($user, $subcategory, $numberProducts = 1){
        $order_products = factory('App\Product', $numberProducts)->create(['subcategory_id' => $subcategory->id])->each(function($product){
            $product['quantity'] = random_int(1, 4);
        })->toArray();

        $data = factory('App\Order')->make()->toArray();
        unset($data['user_id']);
        $data['order_products'] = $order_products;

        $response = $this->actingAs($user)->json('POST', '/orders', $data);

        return $user->order[0];
    }


}
