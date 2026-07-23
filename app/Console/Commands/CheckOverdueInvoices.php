<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Jobs\SendPaymentReminderJob;
use Illuminate\Console\Command;

class CheckOverdueInvoices extends Command
{
    protected $signature = 'invoices:check-overdue';
    protected $description = 'Mark overdue invoices and send reminders';

    public function handle(): int
    {
        $this->info('Checking for overdue invoices...');

        // Mark overdue
        $count = Invoice::whereIn('status', ['sent', 'partial'])
            ->where('due_date', '<', now())
            ->where('status', '!=', 'overdue')
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} invoices as overdue.");

        // Send reminders
        $overdueInvoices = Invoice::overdue()->with('customer.user')->get();

        foreach ($overdueInvoices as $invoice) {
            if ($invoice->customer?->user) {
                SendPaymentReminderJob::dispatch($invoice);
            }
        }

        $this->info("Queued {$overdueInvoices->count()} reminder notifications.");

        return Command::SUCCESS;
    }
}
