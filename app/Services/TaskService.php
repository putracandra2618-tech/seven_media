<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Contracts\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{
    /**
     * Buat task baru untuk user.
     *
     * @param  array<string, mixed>  $data  Data tervalidasi
     * @param  array<int, UploadedFile>|null  $attachments
     */
    public function create(User $user, array $data, ?array $attachments = null): Task
    {
        $payload = $this->preparePayload($data);

        /** @var Task $task */
        $task = $user->tasks()->create($payload);

        if (! empty($data['tag_ids'])) {
            $task->tags()->sync($data['tag_ids']);
        }

        if ($attachments) {
            foreach ($attachments as $file) {
                $this->createAttachment($task, $file);
            }
        }

        return $task->load(['category', 'tags', 'attachments']);
    }

    /**
     * Update task yang sudah ada.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>|null  $attachments
     */
    public function update(Task $task, array $data, ?array $attachments = null): Task
    {
        $payload = $this->preparePayload($data);

        $task->update($payload);

        if (array_key_exists('tag_ids', $data)) {
            $task->tags()->sync($data['tag_ids'] ?? []);
        }

        if (! empty($data['remove_attachments'])) {
            $this->deleteAllAttachments($task);
        }

        if ($attachments) {
            foreach ($attachments as $file) {
                $this->createAttachment($task, $file);
            }
        }

        return $task->fresh(['category', 'tags', 'attachments']);
    }

    /**
     * Hapus task + file lampiran (jika ada).
     */
    public function delete(Task $task): void
    {
        $this->deleteAllAttachments($task);
        $task->delete();
    }

    /**
     * Siapkan field aman untuk mass assignment.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function preparePayload(array $data): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_done' => (bool) ($data['is_done'] ?? false),
            'category_id' => $data['category_id'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ];
    }

    protected function createAttachment(Task $task, UploadedFile $file): void
    {
        $path = $file->store('attachments', 'public');

        $task->attachments()->create([
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }

    protected function deleteAllAttachments(Task $task): void
    {
        foreach ($task->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }
    }

    public function removeAttachment(Task $task, \App\Models\TaskAttachment $attachment): Task
    {
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return $task->fresh();
    }

    public function toggle(Task $task): Task
    {
        $task->update(['is_done' => !$task->is_done]);

        return $task->fresh();
    }
}
