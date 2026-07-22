<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tasks_index(): void
    {
        $response = $this->get('/tasks');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_store_task(): void
    {
        $response = $this->post('/tasks', [
            'title' => 'Task tanpa login',
            'description' => 'Seharusnya ditolak',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Belajar Testing',
            'description' => 'Menulis feature test Laravel',
            'is_done' => false,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Belajar Testing',
        ]);
    }

    public function test_authenticated_user_can_view_own_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Task Saya',
        ]);

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee('Task Saya');
    }

    public function test_authenticated_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Judul Lama',
        ]);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Judul Baru',
            'description' => 'Deskripsi diupdate',
            'is_done' => true,
            'status' => 'done',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Baru',
            'is_done' => true,
        ]);
    }

    public function test_authenticated_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_update_others_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $owner->id,
            'title' => 'Milik Owner',
        ]);

        $response = $this->actingAs($intruder)->put("/tasks/{$task->id}", [
            'title' => 'Diganti Intruder',
            'description' => 'Tidak boleh',
            'is_done' => true,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Milik Owner',
            'user_id' => $owner->id,
        ]);
    }

    public function test_user_cannot_delete_others_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->delete("/tasks/{$task->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_see_others_task_on_show(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->get("/tasks/{$task->id}");

        $response->assertForbidden();
    }

    public function test_store_task_validation_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post('/tasks', [
                'title' => '',
                'description' => 'Deskripsi saja',
            ]);

        $response->assertRedirect(route('tasks.create'));
        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('tasks', 0);
    }
}
