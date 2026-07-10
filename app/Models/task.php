<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    /**
     * Task belongs to one User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}