<?php

namespace App\Http\Controllers;

use App\Models\CustomerRegistration;
use Illuminate\Http\Request;

class PublicVerificationController extends Controller
{
    /**
     * Verify document authenticity (public, no login required).
     */
    public function verify(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return view('public.verify.index', ['result' => null, 'error' => null]);
        }

        $registration = CustomerRegistration::where('id', substr($code, 0, 36))
            ->orWhere('tracking_no', $code)
            ->first();

        if (!$registration) {
            return view('public.verify.index', [
                'result' => null,
                'error' => 'Document not found. Please check the code and try again.'
            ]);
        }

        // Return masked information only - no sensitive data
        $result = [
            'document_type' => 'Customer Registration Slip',
            'issue_date' => $registration->created_at->format('M d, Y'),
            'branch' => $registration->registeredBy?->branch ?? 'Main Office',
            'name_masked' => $this->maskName($registration->name),
            'id_masked' => $this->maskId($registration->id_number),
            'is_valid' => $registration->status !== 'revoked',
            'status' => $registration->status === 'revoked' ? 'Revoked' : 'Valid',
        ];

        return view('public.verify.index', ['result' => $result, 'error' => null]);
    }

    /**
     * Track service status (public, no login required).
     */
    public function track(Request $request)
    {
        $trackingNo = $request->get('tracking_no');
        
        if (!$trackingNo) {
            return view('public.track.index', ['result' => null, 'error' => null]);
        }

        $registration = CustomerRegistration::where('tracking_no', $trackingNo)->first();

        if (!$registration) {
            return view('public.track.index', [
                'result' => null,
                'error' => 'Tracking number not found. Please check and try again.'
            ]);
        }

        // Return status information only
        $result = [
            'tracking_no' => $registration->tracking_no,
            'name_masked' => $this->maskName($registration->name),
            'registered_date' => $registration->created_at->format('M d, Y'),
            'status' => $registration->status,
            'services' => $registration->services->map(function($service) {
                return [
                    'name' => $service->name,
                    'amount' => $service->pivot->amount,
                    'status' => 'Pending', // Placeholder
                ];
            }),
        ];

        return view('public.track.index', ['result' => $result, 'error' => null]);
    }

    /**
     * Mask name for privacy.
     */
    private function maskName(string $name): string
    {
        $parts = explode(' ', $name);
        $masked = [];
        foreach ($parts as $part) {
            if (strlen($part) <= 2) {
                $masked[] = $part;
            } else {
                $masked[] = substr($part, 0, 2) . str_repeat('*', strlen($part) - 2);
            }
        }
        return implode(' ', $masked);
    }

    /**
     * Mask ID number for privacy.
     */
    private function maskId(string $id): string
    {
        $len = strlen($id);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }
        return str_repeat('*', $len - 4) . substr($id, -4);
    }
}
