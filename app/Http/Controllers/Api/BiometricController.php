<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiometricDevice;
use App\Models\BiometricAttendance;
use App\Services\BiometricService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BiometricController extends Controller
{
    protected BiometricService $biometricService;

    public function __construct(BiometricService $biometricService)
    {
        $this->biometricService = $biometricService;
    }

    /**
     * Webhook endpoint for receiving attendance data
     * 
     * Supported formats:
     * - ZKTeco format: POST /api/biometric/webhook/{device_id}
     * - Generic format: POST /api/biometric/webhook/{device_id}
     * - Hikvision format: POST /api/biometric/webhook/{device_id}
     */
    public function webhook(Request $request, string $deviceId): JsonResponse
    {
        // Find device
        $device = BiometricDevice::where('device_id', $deviceId)
            ->orWhere('id', $deviceId)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        if ($device->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Device is not active',
            ], 403);
        }

        // Validate API key if configured
        if ($device->api_key) {
            $providedKey = $request->header('X-API-Key') ?? $request->get('api_key');
            if ($providedKey !== $device->api_key) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API key',
                ], 401);
            }
        }

        try {
            $payload = $request->all();
            
            // Log incoming webhook for debugging
            Log::info('Biometric webhook received', [
                'device' => $device->name,
                'payload' => $payload,
            ]);

            // Process the attendance data
            $results = $this->biometricService->processWebhookPayload($payload, $device);

            return response()->json([
                'success' => true,
                'message' => 'Attendance data processed',
                'records' => $results['records'],
            ]);

        } catch (\Exception $e) {
            Log::error('Biometric webhook error', [
                'device' => $device->name,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing attendance data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * CSV Import endpoint
     */
    public function importCSV(Request $request, int $deviceId): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $device = BiometricDevice::findOrFail($deviceId);

        try {
            $file = $request->file('file');
            $results = $this->biometricService->importFromCSV($device, $file->getRealPath());

            return response()->json([
                'success' => $results['success'],
                'message' => "Imported {$results['imported']} records",
                'errors' => $results['errors'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get attendance records for a device
     */
    public function getRecords(Request $request, int $deviceId): JsonResponse
    {
        $device = BiometricDevice::findOrFail($deviceId);
        
        $query = BiometricAttendance::where('device_id', $deviceId);
        
        // Filter by date range
        if ($request->has('from')) {
            $query->where('punch_time', '>=', Carbon::parse($request->from));
        }
        if ($request->has('to')) {
            $query->where('punch_time', '<=', Carbon::parse($request->to));
        }
        
        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $records = $query->orderBy('punch_time', 'desc')
            ->paginate($request->get('per_page', 50));

        return response()->json($records);
    }

    /**
     * Get employee attendance summary
     */
    public function getEmployeeSummary(Request $request, int $employeeId): JsonResponse
    {
        $startDate = Carbon::parse($request->get('from', now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('to', now()));
        
        $employee = \App\Models\Employee::findOrFail($employeeId);
        $summary = $this->biometricService->getEmployeeSummary($employee, $startDate, $endDate);

        return response()->json($summary);
    }

    /**
     * Test device connection
     */
    public function testConnection(int $deviceId): JsonResponse
    {
        $device = BiometricDevice::findOrFail($deviceId);
        $isOnline = $this->biometricService->testConnection($device);

        return response()->json([
            'success' => true,
            'online' => $isOnline,
            'message' => $isOnline ? 'Device is reachable' : 'Device is not reachable',
        ]);
    }

    /**
     * Manual sync trigger
     */
    public function sync(int $deviceId): JsonResponse
    {
        $device = BiometricDevice::findOrFail($deviceId);
        
        if ($device->sync_method === 'csv') {
            return response()->json([
                'success' => false,
                'message' => 'CSV devices require manual file upload',
            ], 400);
        }

        try {
            $results = $this->biometricService->syncFromDevice($device);

            return response()->json([
                'success' => $results['success'],
                'message' => "Synced {$results['records']} records",
                'errors' => $results['errors'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk sync all active devices
     */
    public function syncAll(): JsonResponse
    {
        $devices = BiometricDevice::active()
            ->whereNotNull('ip_address')
            ->get();

        $results = [];
        $totalRecords = 0;

        foreach ($devices as $device) {
            try {
                $syncResults = $this->biometricService->syncFromDevice($device);
                $results[$device->id] = [
                    'device' => $device->name,
                    'success' => $syncResults['success'],
                    'records' => $syncResults['records'],
                ];
                $totalRecords += $syncResults['records'];
            } catch (\Exception $e) {
                $results[$device->id] = [
                    'device' => $device->name,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'total_records' => $totalRecords,
            'devices' => $results,
        ]);
    }

    /**
     * Get device status
     */
    public function status(): JsonResponse
    {
        $devices = BiometricDevice::with('branch')->get();
        
        $status = [
            'total' => $devices->count(),
            'active' => $devices->where('status', 'active')->count(),
            'inactive' => $devices->where('status', 'inactive')->count(),
            'offline' => $devices->where('status', 'offline')->count(),
            'devices' => $devices->map(function ($device) {
                return [
                    'id' => $device->id,
                    'name' => $device->name,
                    'brand' => $device->brand,
                    'status' => $device->status,
                    'last_sync' => $device->last_sync_at?->toIso8601String(),
                    'branch' => $device->branch?->name,
                ];
            }),
        ];

        return response()->json($status);
    }
}
