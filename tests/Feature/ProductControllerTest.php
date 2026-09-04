<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_authenticated_user_can_view_products(): void
    {
        Product::create([
            'name' => 'Test Product',
        ]);

        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_can_create_product(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'New Product',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
        ]);
    }

    public function test_can_update_product(): void
    {
        $product = Product::create([
            'name' => 'Old Product',
        ]);

        $response = $this->actingAs($this->user)->put("/products/{$product->id}", [
            'name' => 'Updated Product',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'To Delete',
        ]);

        $response = $this->actingAs($this->user)->delete("/products/{$product->id}");

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_api_products_list(): void
    {
        Product::create([
            'name' => 'API Product 1',
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJson(['API Product 1']);
    }
}
