<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Chapter extends Model implements Sortable
{
    use SortableTrait;


    protected $fillable = ['course_id', 'title', 'content', 'sort_order'];


    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }


    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
