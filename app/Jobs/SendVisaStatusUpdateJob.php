<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\VisaApplication;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVisaStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public VisaApplication $application,
        public string $previousStatus,
        public string $newStatus
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $user = $this->application->customer?->user;

        if (!$user) {
            return;
        }

        $notificationService->sendVisaStatusUpdate(
            $user,
            $this->application,
            $this->newStatus
        );
    }
}
