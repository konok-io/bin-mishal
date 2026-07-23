<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOverdueInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): int
    {
        $count = Invoice::whereIn('status', ['sent', 'partial'])
            ->where('due_date', '<', now())
            ->whereNotNull('paid_date') // Already overdue but not marked
            ->update(['status' => 'overdue']);

        return $count;
    }
}
