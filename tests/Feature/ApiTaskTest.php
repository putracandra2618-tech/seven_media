<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'api@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'user', 'token']);
    }

    public function test_api_guest_cannot_list_tasks(): void
    {
        $this->getJson('/api/tasks')->assertUnauthorized();
    }

    public function test_api_user_can_create_task(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/tasks', [
            'title' => 'Task API',
            'description' => 'Dari feature test API',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Task API');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Task API',
        ]);
    }

    public function test_api_user_cannot_update_others_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($intruder);

        $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Hacked',
            'description' => 'Tidak boleh',
        ])->assertForbidden();
    }
}
