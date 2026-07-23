<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBusinessSummary extends Command
{
    protected $signature = 'reports:send-summary';
    protected $description = 'Send weekly business summary to management';

    public function handle(): int
    {
        $this->info('Generating business summary...');

        $startDate = now()->subWeek();
        $endDate = now();

        $data = [
            'period' => $startDate->format('d M') . ' - ' . $endDate->format('d M Y'),
            'bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'revenue' => Payment::whereBetween('paid_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('amount'),
            'new_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_leads' => Lead::whereBetween('created_at', [$startDate, $endDate])->count(),
            'lead_conversion' => Lead::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'converted')
                ->count(),
        ];

        $this->table(
            ['Metric', 'Value'],
            [
                ['Bookings', $data['bookings']],
                ['Revenue', 'SAR ' . number_format($data['revenue'], 2)],
                ['New Customers', $data['new_customers']],
                ['New Leads', $data['new_leads']],
                ['Lead Conversion', $data['lead_conversion']],
            ]
        );

        // Send email to management
        // Mail::to(config('binmishal.company.email'))->send(new BusinessSummaryMail($data));

        $this->info('Business summary sent.');

        return Command::SUCCESS;
    }
}
