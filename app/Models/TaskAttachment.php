<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    protected $fillable = [
        'task_id',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * TaskAttachment belongs to one Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Dapatkan ukuran file dalam format KB/MB
     */
    public function getFormattedSize(): string
    {
        if ($this->size < 1024) {
            return $this->size . ' B';
        } elseif ($this->size < 1024 * 1024) {
            return round($this->size / 1024, 2) . ' KB';
        } else {
            return round($this->size / (1024 * 1024), 2) . ' MB';
        }
    }

    /**
     * Check if attachment is image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get file extension
     */
    public function getExtension(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }
}
