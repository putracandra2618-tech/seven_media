<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Rule;

trait TaskRules
{
    public function taskRules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_done' => ['sometimes', 'boolean'],
            'due_date' => ['nullable', 'date'],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where(fn ($q) => $q->where('user_id', auth()->id())),
            ],
            'attachments' => ['nullable', 'array', 'max:6'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,gif,webp', 'max:1024'],
            'remove_attachments' => ['nullable', 'boolean'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => [
                'integer',
                Rule::exists('tags', 'id'),
            ],
        ];
    }
}
