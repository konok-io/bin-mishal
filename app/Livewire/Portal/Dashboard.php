<?php

namespace App\Livewire\Portal;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\VisaApplication;
use App\Models\Document;
use App\Models\Appointment;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];
    public array $recentBookings = [];
    public array $upcomingAppointments = [];
    public array $pendingDocuments = [];

    public function mount()
    {
        $user = auth()->user();
        $customer = $user->customer;

        if (!$customer) {
            return;
        }

        $this->loadStats($customer);
        $this->loadRecentBookings($customer);
        $this->loadUpcomingAppointments($customer);
        $this->loadPendingDocuments($customer);
    }

    private function loadStats($customer)
    {
        $this->stats = [
            'total_bookings' => $customer->bookings()->count(),
            'total_paid' => Payment::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_visas' => VisaApplication::where('customer_id', $customer->id)
                ->whereNotIn('status', ['approved', 'rejected', 'delivered'])
                ->count(),
            'upcoming_appointments' => Appointment::where('customer_id', $customer->id)
                ->where('preferred_date', '>=', now())
                ->where('status', 'confirmed')
                ->count(),
        ];
    }

    private function loadRecentBookings($customer)
    {
        $this->recentBookings = $customer->bookings()
            ->with('payments')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'booking_no' => $b->booking_no,
                'type' => $b->booking_type,
                'status' => $b->booking_status,
                'total' => $b->total_amount,
                'paid' => $b->paid_amount,
                'date' => $b->created_at->format('d M Y'),
            ])
            ->toArray();
    }

    private function loadUpcomingAppointments($customer)
    {
        $this->upcomingAppointments = Appointment::where('customer_id', $customer->id)
            ->where('preferred_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('preferred_date')
            ->limit(3)
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'date' => $a->preferred_date->format('d M Y'),
                'time' => $a->preferred_time,
                'service' => $a->service_type,
                'status' => $a->status,
            ])
            ->toArray();
    }

    private function loadPendingDocuments($customer)
    {
        $this->pendingDocuments = Document::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->limit(5)
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'type' => $d->document_type,
                'status' => $d->status,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.portal.dashboard');
    }
}
