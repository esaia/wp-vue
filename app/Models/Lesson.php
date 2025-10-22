<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = ['chapter_id', 'title', 'content', 'order'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
