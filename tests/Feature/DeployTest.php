<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeployTest extends TestCase
{
    public function test_deploy_page_can_be_rendered(): void
    {
        $response = $this->get('/deploy');

        $response->assertStatus(200);
        $response->assertViewIs('deploy');
        $response->assertSee('Deploy App');
    }

    public function test_deploy_fails_without_password(): void
    {
        $response = $this->postJson('/deploy', []);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Password is required.',
        ]);
    }

    public function test_deploy_fails_with_invalid_password(): void
    {
        $response = $this->postJson('/deploy', [
            'password' => 'wrong-password-123',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid deployment password.',
        ]);
    }
}
