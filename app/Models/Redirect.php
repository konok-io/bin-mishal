<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_url',
        'new_url',
        'type',
        'notes',
    ];

    /**
     * Get redirect type as integer (for HTTP headers).
     */
    public function getTypeIntAttribute(): int
    {
        return (int) $this->type;
    }
}
