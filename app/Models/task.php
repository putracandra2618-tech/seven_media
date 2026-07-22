<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


class Task extends Model
{
    use HasFactory, SoftDeletes;


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
     * Task belongs to many tags
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag')->withTimestamps()->withPivot('tagged_at');
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

    public function attachmentUrl(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        return asset('storage/' . $this->attachment);
    }

    public function isImageAttachment(): bool
    {
        if (!$this->attachment) {
            return false;
        }

        $ext = strtolower(pathinfo($this->attachment, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }

    public function dueDate(): ?string
    {
        if (!$this->due_date) {
            return null;
        }

        return $this->due_date->format('Y-m-d');
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->due_date->isBefore(now());
    }
}