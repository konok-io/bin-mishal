<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Document;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDocumentExpiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $daysBeforeExpiry = 90
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $expiryDate = now()->addDays($this->daysBeforeExpiry);

        $documents = Document::where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->where('notify_expiry', true)
            ->with('customer.user')
            ->get();

        foreach ($documents as $document) {
            $user = $document->customer?->user;

            if ($user) {
                $notificationService->sendDocumentExpiryReminder(
                    $user,
                    $document,
                    $this->daysBeforeExpiry
                );
            }
        }
    }
}
