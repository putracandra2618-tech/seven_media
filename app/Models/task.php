<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


class Task extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'is_done',
        'due_date',
        'attachment',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'due_date' => 'date',
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

    /**
     * Task has many attachments
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Dapatkan ukuran file attachment dalam format KB/MB
     */
    public function getAttachmentSize(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        $size = Storage::disk('public')->size($this->attachment);
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1024 * 1024) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / (1024 * 1024), 2) . ' MB';
        }
    }
}