<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'session_id',
        'original_path',
        'processed_path',
        'applied_effect',
        'corners_json',
        'is_cropped',
    ];

    protected $casts = [
        'corners_json' => 'array',
        'is_cropped' => 'boolean',
    ];
}
