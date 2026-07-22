<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_categories_index(): void
    {
        $response = $this->get('/categories');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_store_category(): void
    {
        $response = $this->post('/categories', [
            'name' => 'Kategori tanpa login',
            'color' => 'primary',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_authenticated_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Kategori Baru',
            'color' => 'success',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Kategori Baru',
        ]);
    }

    public function test_authenticated_user_can_update_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Nama Lama',
        ]);

        $response = $this->actingAs($user)->put("/categories/{$category->id}", [
            'name' => 'Nama Baru',
            'color' => 'danger',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Nama Baru',
        ]);
    }

    public function test_authenticated_user_can_delete_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_update_others_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $category = Category::factory()->create([
            'user_id' => $owner->id,
            'name' => 'Milik Owner',
        ]);

        $response = $this->actingAs($intruder)->put("/categories/{$category->id}", [
            'name' => 'Diganti Intruder',
            'color' => 'warning',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Milik Owner',
        ]);
    }

    public function test_user_cannot_delete_others_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->delete("/categories/{$category->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
