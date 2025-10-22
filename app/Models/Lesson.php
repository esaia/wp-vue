<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Lesson extends Model implements Sortable
{
    use SortableTrait;


    protected $fillable = ['course_id', 'chapter_id', 'title', 'content', 'sort_order'];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];


    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }


    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
