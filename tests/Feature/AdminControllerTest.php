<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->regularUser = User::factory()->create([
            'role' => 'worker',
        ]);
    }

    public function test_non_admin_cannot_access_admin_users(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/users');
        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_admin_users(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertViewIs('admin.users');
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'New Staff',
            'username' => 'newstaff',
            'password' => 'password123',
            'role' => 'worker',
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'username' => 'newstaff',
            'role' => 'worker',
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/users/{$this->admin->id}");

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
