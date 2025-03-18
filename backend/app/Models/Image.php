<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = ['user_id', 'path', 'label', 'uploaded_at'];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the user that owns the image.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
