<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Ambil category dari route parameter
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')
                    ->where(fn($query) => $query->where('user_id', auth()->id()))
                    ->ignore($category->id),
            ],
            'color' => 'required|in:primary,success,danger,warning,info,secondary',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'color' => 'warna',
        ];
    }
}
