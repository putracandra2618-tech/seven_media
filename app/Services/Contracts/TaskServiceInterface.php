<?php

namespace App\Services\Contracts;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;

interface TaskServiceInterface
{
    public function create(User $user, array $data, ?array $attachments = null): Task;
    public function update(Task $task, array $data, ?array $attachments = null): Task;
    public function delete(Task $task): void;
    public function removeAttachment(Task $task, \App\Models\TaskAttachment $attachment): Task;
    public function toggle(Task $task): Task;
}
