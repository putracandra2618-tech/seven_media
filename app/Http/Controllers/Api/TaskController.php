<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $tasks
    ) {}

    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()
            ->tasks()
            ->with(['category', 'tags'])
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(10);

        return TaskResource::collection($tasks)->response();
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->tasks->create(
            $request->user(),
            $request->validated(),
            $request->file('attachment')
        );

        return response()->json([
            'message' => 'Task berhasil dibuat.',
            'data' => new TaskResource($task),
        ], 201);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $task->load(['category', 'tags']);

        return response()->json(['data' => new TaskResource($task)]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $task = $this->tasks->update(
            $task,
            $request->validated(),
            $request->file('attachment')
        );

        return response()->json([
            'message' => 'Task berhasil diupdate.',
            'data' => new TaskResource($task),
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! $request->user()->tokenCan('tasks:write')) {
            return response()->json(['message' => 'Token tidak memiliki izin untuk menghapus task.'], 403);
        }

        $this->tasks->delete($task);

        return response()->json([
            'message' => 'Task berhasil dihapus.',
        ]);
    }
}