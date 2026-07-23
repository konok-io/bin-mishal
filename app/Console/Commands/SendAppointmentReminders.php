<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Jobs\SendAppointmentReminderJob;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send appointment reminders for tomorrow';

    public function handle(): int
    {
        $this->info('Sending appointment reminders...');

        $tomorrow = now()->addDay()->startOfDay();
        $tomorrowEnd = now()->addDay()->endOfDay();

        $appointments = Appointment::whereBetween('preferred_date', [$tomorrow, $tomorrowEnd])
            ->where('status', 'confirmed')
            ->with('customer.user')
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->customer?->user) {
                SendAppointmentReminderJob::dispatch($appointment);
            }
        }

        $this->info("Queued {$appointments->count()} appointment reminders.");

        return Command::SUCCESS;
    }
}
