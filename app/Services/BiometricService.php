<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BiometricDevice;
use App\Models\BiometricAttendance;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class BiometricService
{
    protected BiometricDevice $device;
    protected $zkConnector = null;

    /**
     * Sync attendance from device
     */
    public function syncFromDevice(BiometricDevice $device): array
    {
        $this->device = $device;
        
        $results = [
            'success' => true,
            'records' => 0,
            'errors' => [],
        ];

        try {
            switch ($device->sync_method) {
                case 'webhook':
                    $this->processWebhookData();
                    break;
                    
                case 'polling':
                    $this->pollDevice();
                    break;
                    
                case 'csv':
                    // CSV is handled via file upload
                    break;
                    
                case 'manual':
                default:
                    $this->manualSync();
                    break;
            }

            $device->markSynced();
            $results['records'] = $device->attendance()->whereDate('punch_time', today())->count();
            
        } catch (\Exception $e) {
            $results['success'] = false;
            $results['errors'][] = $e->getMessage();
            Log::error('Biometric sync failed', [
                'device' => $device->name,
                'error' => $e->getMessage(),
            ]);
            
            $device->markOffline();
        }

        return $results;
    }

    /**
     * Process attendance data from webhook
     */
    protected function processWebhookData(): void
    {
        // Webhook data is processed in BiometricController via API endpoint
        // This method is for validation and processing
    }

    /**
     * Poll ZKTeco device for new attendance records
     */
    protected function pollDevice(): void
    {
        if (!$this->device->ip_address) {
            throw new \Exception('Device IP not configured');
        }

        // For ZKTeco devices, use socket connection
        if ($this->device->brand === 'zkteco') {
            $this->pollZKTeco();
        } elseif ($this->device->brand === 'hikvision') {
            $this->pollHikvision();
        } else {
            // Generic polling - assume similar to ZKTeco
            $this->pollZKTeco();
        }
    }

    /**
     * Poll ZKTeco device using socket connection
     */
    protected function pollZKTeco(): void
    {
        try {
            $socket = @fsockopen(
                $this->device->ip_address,
                $this->device->port,
                $errno,
                $errstr,
                5
            );

            if (!$socket) {
                throw new \Exception("Cannot connect to {$this->device->ip_address}:{$this->device->port}");
            }

            // Read attendance log - ZKTeco format
            // This is a simplified example; real implementation needs ZKTeco SDK
            $data = $this->readZKTecoLog($socket);
            $this->processAttendanceData($data);
            
            fclose($socket);
            
        } catch (\Exception $e) {
            Log::warning('ZKTeco polling failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Read attendance log from ZKTeco device
     */
    protected function readZKTecoLog($socket): array
    {
        // ZKTeco protocol command to get attendance log
        // Real implementation would use proper SDK
        // This is a placeholder for the actual SDK integration
        
        $records = [];
        
        // Example: Parse raw data from device
        // Command structure varies by device firmware version
        
        return $records;
    }

    /**
     * Poll Hikvision device via HTTP API
     */
    protected function pollHikvision(): void
    {
        if (!$this->device->api_key) {
            throw new \Exception('Hikvision API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Digest ' . $this->device->api_key,
        ])->get("http://{$this->device->ip_address}/ISAPI/AccessControl/AcsEvent", [
            'format' => 'stream',
            'searchID' => uniqid(),
            'maxResults' => 100,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->processHikvisionData($data);
        } else {
            throw new \Exception('Hikvision API request failed');
        }
    }

    /**
     * Process Hikvision attendance data
     */
    protected function processHikvisionData(array $data): void
    {
        $events = $data['AcsEvent']['EventList'] ?? [];
        
        foreach ($events as $event) {
            BiometricAttendance::updateOrCreate(
                [
                    'device_id' => $this->device->id,
                    'employee_bio_id' => $event['employeeNoString'] ?? '',
                    'punch_time' => Carbon::parse($event['time']),
                    'punch_type' => $this->mapHikvisionEventType($event['eventType'] ?? ''),
                ],
                [
                    'verify_mode' => $this->mapHikvisionVerifyMode($event['verifyType'] ?? ''),
                    'location' => $event['cardReaderName'] ?? null,
                ]
            );
        }
    }

    /**
     * Manual sync - process existing unsynced records
     */
    protected function manualSync(): void
    {
        $this->processUnsyncedRecords();
    }

    /**
     * Process attendance data from various sources
     */
    public function processAttendanceData(array $records): void
    {
        foreach ($records as $record) {
            $bioRecord = BiometricAttendance::updateOrCreate(
                [
                    'device_id' => $this->device->id,
                    'employee_bio_id' => $record['employee_id'] ?? '',
                    'punch_time' => Carbon::parse($record['timestamp']),
                    'punch_type' => $record['type'] ?? 'check_in',
                ],
                [
                    'verify_mode' => $record['verifyMode'] ?? 'finger',
                    'location' => $record['location'] ?? null,
                ]
            );

            // Link to employee if bio_id matches
            $this->linkEmployeeToRecord($bioRecord, $record['employee_id'] ?? '');
            
            // Sync to main attendance
            $bioRecord->processAndSyncToAttendance($bioRecord);
        }
    }

    /**
     * Link biometric record to employee
     */
    protected function linkEmployeeToRecord(BiometricAttendance $record, string $bioId): void
    {
        if ($record->employee_id) {
            return;
        }

        // Find employee by biometric ID
        $employee = Employee::where('biometric_id', $bioId)
            ->orWhere('employee_id', $bioId)
            ->first();

        if ($employee) {
            $record->update(['employee_id' => $employee->id]);
        }
    }

    /**
     * Process all unsynced biometric records
     */
    protected function processUnsyncedRecords(): void
    {
        $unsyncedRecords = BiometricAttendance::unsynced()
            ->with('device')
            ->limit(1000)
            ->get();

        foreach ($unsyncedRecords as $record) {
            $record->processAndSyncToAttendance($record);
        }
    }

    /**
     * Import attendance from CSV
     */
    public function importFromCSV(BiometricDevice $device, $file): array
    {
        $results = [
            'success' => true,
            'imported' => 0,
            'errors' => [],
        ];

        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new \Exception('Cannot open CSV file');
        }

        // Skip header
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle)) !== false) {
                // Expected format: employee_id, timestamp, type, verify_mode, location
                if (count($data) < 3) {
                    continue;
                }

                $record = BiometricAttendance::create([
                    'device_id' => $device->id,
                    'employee_bio_id' => $data[0],
                    'punch_time' => Carbon::parse($data[1]),
                    'punch_type' => $data[2] ?? 'check_in',
                    'verify_mode' => $data[3] ?? 'finger',
                    'location' => $data[4] ?? null,
                ]);

                $this->linkEmployeeToRecord($record, $data[0]);
                $record->processAndSyncToAttendance($record);
                
                $results['imported']++;
            }

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $results['success'] = false;
            $results['errors'][] = $e->getMessage();
        }

        fclose($handle);
        $device->markSynced();

        return $results;
    }

    /**
     * Process webhook attendance data
     */
    public function processWebhookPayload(array $payload, BiometricDevice $device): array
    {
        $results = [
            'success' => true,
            'records' => 0,
        ];

        $records = $payload['attendance'] ?? [$payload];

        foreach ($records as $record) {
            BiometricAttendance::updateOrCreate(
                [
                    'device_id' => $device->id,
                    'employee_bio_id' => $record['employee_id'] ?? $record['user_id'] ?? '',
                    'punch_time' => Carbon::parse($record['timestamp'] ?? $record['punch_time'] ?? now()),
                    'punch_type' => $record['type'] ?? $record['punch_type'] ?? 'check_in',
                ],
                [
                    'verify_mode' => $record['verify_mode'] ?? $record['verifyType'] ?? 'finger',
                    'location' => $record['location'] ?? $record['door'] ?? null,
                ]
            );

            $results['records']++;
        }

        $device->update(['last_sync_at' => now()]);

        return $results;
    }

    /**
     * Get attendance summary for employee
     */
    public function getEmployeeSummary(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        $biometricRecords = BiometricAttendance::where('employee_id', $employee->id)
            ->whereBetween('punch_time', [$startDate, $endDate])
            ->orderBy('punch_time')
            ->get();

        $summary = [
            'total_punches' => $biometricRecords->count(),
            'check_ins' => $biometricRecords->whereIn('punch_type', ['check_in', 'break_in'])->count(),
            'check_outs' => $biometricRecords->whereIn('punch_type', ['check_out', 'break_out'])->count(),
            'first_punch' => $biometricRecords->min('punch_time'),
            'last_punch' => $biometricRecords->max('punch_time'),
            'records' => $biometricRecords,
        ];

        return $summary;
    }

    /**
     * Map Hikvision event type to our format
     */
    protected function mapHikvisionEventType(string $eventType): string
    {
        $mapping = [
            'Entry' => 'check_in',
            'Exit' => 'check_out',
            'BreakOut' => 'break_out',
            'BreakIn' => 'break_in',
        ];

        return $mapping[$eventType] ?? 'check_in';
    }

    /**
     * Map Hikvision verify mode to our format
     */
    protected function mapHikvisionVerifyMode(string $verifyType): string
    {
        $mapping = [
            'Card' => 'card',
            'Finger' => 'finger',
            'Face' => 'face',
            'Password' => 'password',
        ];

        return $mapping[$verifyType] ?? 'other';
    }

    /**
     * Test device connection
     */
    public function testConnection(BiometricDevice $device): bool
    {
        if ($device->brand === 'hikvision') {
            try {
                $response = Http::timeout(5)
                    ->withHeaders(['Authorization' => 'Digest ' . ($device->api_key ?? '')])
                    ->get("http://{$device->ip_address}/ISAPI/System/deviceInfo");
                
                return $response->successful();
            } catch (\Exception $e) {
                return false;
            }
        }

        // TCP connection test for other devices
        $socket = @fsockopen($device->ip_address, $device->port, $errno, $errstr, 3);
        
        if ($socket) {
            fclose($socket);
            return true;
        }

        return false;
    }
}
