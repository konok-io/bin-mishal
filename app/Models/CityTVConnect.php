<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityTVConnect extends Model
{
    use HasFactory;

    protected $table = 'city_tv_connects';

    protected $fillable = [
        'branch_id',
        'name',
        'serial_number',
        'password',
        'ip_address',
        'port',
        'status',
        'notes',
        'last_sync',
    ];

    protected $casts = [
        'last_sync' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
