<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_view_orders(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_orders(): void
    {
        $response = $this->actingAs($this->user)->get('/orders');
        $response->assertStatus(200);
        $response->assertViewIs('orders.index');
    }

    public function test_can_create_order_with_items(): void
    {
        $payload = [
            'customer_name' => 'John Doe',
            'phone' => '012345678',
            'address' => 'Phnom Penh, Cambodia',
            'payment_type' => 'Cash',
            'payment_status' => 'Unpaid',
            'delivery_type' => 'Express',
            'items' => [
                [
                    'product_name' => 'Item A',
                    'quantity' => 2,
                    'unit_price' => 15.00,
                ],
                [
                    'product_name' => 'Item B',
                    'quantity' => 1,
                    'unit_price' => 20.00,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->post('/orders', $payload);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'phone' => '012345678',
            'total_price' => 50.00,
            'status' => 'In Progress',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Item A',
            'quantity' => 2,
            'unit_price' => 15.00,
            'total_price' => 30.00,
        ]);
    }

    public function test_can_update_order_status(): void
    {
        $order = Order::create([
            'order_number' => 'ord0001',
            'customer_name' => 'Jane',
            'phone' => '098765432',
            'address' => 'Siem Reap',
            'payment_type' => 'ABA',
            'payment_status' => 'Paid',
            'delivery_type' => 'Standard',
            'status' => 'In Progress',
            'total_price' => 25.00,
        ]);

        $response = $this->actingAs($this->user)
            ->patchJson("/orders/{$order->id}/status", [
                'status' => 'Completed',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'Completed',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Completed',
        ]);
    }

    public function test_can_delete_order(): void
    {
        $order = Order::create([
            'order_number' => 'ord0002',
            'customer_name' => 'Bob',
            'phone' => '098765432',
            'address' => 'Kampot',
            'payment_type' => 'Cash',
            'payment_status' => 'Unpaid',
            'delivery_type' => 'Standard',
            'status' => 'In Progress',
            'total_price' => 10.00,
        ]);

        $order->orderItems()->create([
            'product_name' => 'Sample Item',
            'quantity' => 1,
            'unit_price' => 10.00,
            'total_price' => 10.00,
        ]);

        $response = $this->actingAs($this->user)->delete("/orders/{$order->id}");

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['product_name' => 'Sample Item']);
    }

    public function test_api_latest_orders(): void
    {
        $order = Order::create([
            'order_number' => 'ord0003',
            'customer_name' => 'Alice',
            'phone' => '098765432',
            'address' => 'Battambang',
            'payment_type' => 'Cash',
            'payment_status' => 'Unpaid',
            'delivery_type' => 'Standard',
            'status' => 'In Progress',
            'total_price' => 10.00,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/orders/latest?since_id=0');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'count',
            'max_id',
            'orders',
            'updated_statuses',
            'active_ids',
            'server_time',
        ]);
    }
}
