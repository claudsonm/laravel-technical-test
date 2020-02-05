<?php

namespace Tests\Feature;

use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_authorized_requests_are_allowed_to_list_the_orders()
    {
        $this->getJson('api/orders')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function a_list_of_orders_can_be_retrieved()
    {
        $this->signInAsClient();
        $orders = create(Order::class, [], 20);

        $this->getJson('api/orders')
            ->assertSuccessful()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonFragment(['name' => $orders->first()->destination])
            ->assertDontSee($orders->last()->destination);
    }

    /** @test */
    public function more_orders_can_be_retrieved_using_query_parameters()
    {
        $this->signInAsClient();
        $orders = create(Order::class, [], 20);

        $this->getJson('api/orders?page=2')
            ->assertSuccessful()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonFragment(['current_page' => 2])
            ->assertJsonFragment(['name' => $orders->last()->destination])
            ->assertDontSee($orders->first()->destination);
    }

    /** @test */
    public function only_authorized_requests_are_allowed_to_get_an_order_data()
    {
        create(Order::class);
        $this->getJson('api/orders/1')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function it_can_display_the_data_of_a_single_order()
    {
        $this->signInAsClient();
        $order = create(Order::class);

        $this->getJson('api/orders/1')
            ->assertSuccessful()
            ->assertJsonFragment(['name' => $order->destination])
            ->assertJsonFragment(['address' => $order->address]);
    }

    /** @test */
    public function an_error_is_thrown_when_trying_to_get_data_of_non_existing_order()
    {
        $this->signInAsClient();

        $this->getJson('api/orders/41')
            ->assertNotFound()
            ->assertExactJson(['message' => 'No results for the given resource.']);
    }
}
