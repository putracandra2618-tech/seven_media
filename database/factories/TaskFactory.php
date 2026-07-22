<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => null,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'is_done' => false,
            'due_date' => fake()->optional()->date(),
            'status' => 'pending', // tambahkan status karena model kita punya kolom status!
            'priority' => 'medium', // tambahkan priority juga!
        ];
    }

    public function done(): static
    {
        return $this->state(fn () => ['is_done' => true, 'status' => 'done']);
    }
}
