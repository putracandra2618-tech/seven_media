<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Concerns\TaskRules;

class UpdateTaskRequest extends FormRequest
{
    use TaskRules;

    public function authorize(): bool
    {
        $task = $this->route('task');

        // Pastikan task milik user yang login
        // (Policy dari Modul 11 juga bisa dipakai di sini)
        return auth()->check()
            && $task
            && $task->user_id === auth()->id();
    }

    protected function prepareForValidation(): void
    {
        if ($this->category_id === '' || $this->category_id === null) {
            $this->merge(['category_id' => null]);
        }
    }

    public function rules(): array
    {
        return $this->taskRules();
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'description' => 'deskripsi',
            'attachments' => 'lampiran',
            'attachments.*' => 'lampiran',
            'due_date' => 'tanggal jatuh tempo',
            'category_id' => 'kategori',
            'tag_ids' => 'tag',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul task wajib diisi.',
            'title.min' => 'Judul minimal 3 karakter.',
            'attachments.*.mimes' => 'Lampiran harus berupa JPG, PNG, GIF, WEBP, atau PDF.',
            'attachments.*.max' => 'Ukuran lampiran maksimal 1 MB.',
            'attachments.max' => 'Maksimal 6 lampiran.',
        ];
    }
}