<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricAttendance extends Model
{
    use HasFactory;

    protected $table = 'biometric_attendance';

    protected $fillable = [
        'device_id',
        'employee_id',
        'employee_bio_id',
        'punch_time',
        'punch_type',
        'verify_mode',
        'location',
        'is_synced',
        'synced_at',
    ];

    protected $casts = [
        'punch_time' => 'datetime',
        'synced_at' => 'datetime',
        'is_synced' => 'boolean',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeUnsynced($query)
    {
        return $query->where('is_synced', false);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('punch_time', $date);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeCheckIn($query)
    {
        return $query->whereIn('punch_type', ['check_in', 'break_in', 'overtime_in']);
    }

    public function scopeCheckOut($query)
    {
        return $query->whereIn('punch_type', ['check_out', 'break_out', 'overtime_out']);
    }

    public function isCheckIn(): bool
    {
        return in_array($this->punch_type, ['check_in', 'break_in', 'overtime_in']);
    }

    public function isCheckOut(): bool
    {
        return in_array($this->punch_type, ['check_out', 'break_out', 'overtime_out']);
    }

    public function markSynced(): void
    {
        $this->update([
            'is_synced' => true,
            'synced_at' => now(),
        ]);
    }

    public static function processAndSyncToAttendance(BiometricAttendance $bioRecord): ?Attendance
    {
        if (!$bioRecord->employee_id) {
            return null;
        }

        $date = $bioRecord->punch_time->toDateString();
        
        // Find or create daily attendance record
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $bioRecord->employee_id,
                'date' => $date,
            ],
            [
                'status' => 'present',
                'check_in' => $bioRecord->punch_time,
            ]
        );

        // Update check-in if this is earlier
        if ($bioRecord->isCheckIn()) {
            if (!$attendance->check_in || $bioRecord->punch_time->lt($attendance->check_in)) {
                $attendance->update(['check_in' => $bioRecord->punch_time]);
                
                // Check if late
                $lateThreshold = strtotime($date . ' 09:00:00');
                if (strtotime($bioRecord->punch_time) > $lateThreshold) {
                    $attendance->update([
                        'is_late' => true,
                        'remarks' => 'Late arrival (Biometric)',
                    ]);
                }
            }
        }

        // Update check-out if this is later
        if ($bioRecord->isCheckOut()) {
            if (!$attendance->check_out || $bioRecord->punch_time->gt($attendance->check_out)) {
                $attendance->update(['check_out' => $bioRecord->punch_time]);
                
                // Calculate working hours
                if ($attendance->check_in && $attendance->check_out) {
                    $hours = $attendance->check_in->diffInMinutes($attendance->check_out) / 60;
                    $attendance->update(['working_hours' => round($hours, 2)]);
                }
            }
        }

        $bioRecord->markSynced();
        
        return $attendance;
    }
}
