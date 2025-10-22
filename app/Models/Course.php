<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'slug', 'price'];



    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}
