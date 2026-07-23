<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Jobs\CheckDocumentExpiryJob;
use Illuminate\Console\Command;

class CheckDocumentExpiry extends Command
{
    protected $signature = 'documents:check-expiry {--days=90 : Days before expiry to check}';
    protected $description = 'Check for expiring documents and send notifications';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info("Checking documents expiring within {$days} days...");

        $expiryDate = now()->addDays($days);

        $count = Document::where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->where('notify_expiry', true)
            ->count();

        CheckDocumentExpiryJob::dispatch($days);

        $this->info("Queued expiry check for {$count} documents.");

        return Command::SUCCESS;
    }
}
