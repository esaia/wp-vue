<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseAccess extends Model
{
    use HasFactory;

    protected $table = 'course_access';

    protected $fillable = [
        'user_email',
        'product_id',
        'payment_id',
        'checkout_session_id',
        'access_granted_at',
        'access_expires_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'access_granted_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'metadata' => 'array',
    ];
}
