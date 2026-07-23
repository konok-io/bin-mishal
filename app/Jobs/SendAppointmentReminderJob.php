<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $user = $this->appointment->customer?->user;

        if (!$user) {
            return;
        }

        $notificationService->sendAppointmentReminder($user, $this->appointment);
    }
}
