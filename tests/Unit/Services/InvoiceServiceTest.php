<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Services\Invoice\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    private InvoiceService $service;
    private Customer $customer;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InvoiceService();
        
        $this->customer = Customer::factory()->create();
        $this->booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'total_amount' => 2000,
            'paid_amount' => 500,
        ]);
    }

    public function test_can_create_invoice(): void
    {
        $invoice = $this->service->createInvoice(
            $this->booking,
            $this->customer,
            ['service_fee' => 100]
        );

        $this->assertDatabaseHas('invoices', [
            'booking_id' => $this->booking->id,
            'customer_id' => $this->customer->id,
        ]);
        
        $this->assertEquals(2100, $invoice->subtotal);
        $this->assertEquals(2100, $invoice->total);
    }

    public function test_invoice_has_correct_due_date(): void
    {
        $invoice = $this->service->createInvoice($this->booking, $this->customer);
        
        $this->assertEquals(
            now()->addDays(30)->toDateString(),
            $invoice->due_date->toDateString()
        );
    }

    public function test_can_mark_invoice_as_paid(): void
    {
        $invoice = Invoice::factory()->create([
            'booking_id' => $this->booking->id,
            'customer_id' => $this->customer->id,
            'total' => 2000,
            'balance' => 2000,
        ]);

        $result = $this->service->markAsPaid($invoice, 1000);

        $this->assertEquals(1000, $invoice->fresh()->balance);
        $this->assertEquals('partial', $invoice->fresh()->status);
    }

    public function test_can_mark_invoice_fully_paid(): void
    {
        $invoice = Invoice::factory()->create([
            'booking_id' => $this->booking->id,
            'customer_id' => $this->customer->id,
            'total' => 2000,
            'balance' => 2000,
        ]);

        $result = $this->service->markAsPaid($invoice, 2000);

        $this->assertEquals(0, $invoice->fresh()->balance);
        $this->assertEquals('paid', $invoice->fresh()->status);
    }

    public function test_generates_pdf(): void
    {
        $invoice = Invoice::factory()->create([
            'booking_id' => $this->booking->id,
            'customer_id' => $this->customer->id,
        ]);

        $pdf = $this->service->generatePdf($invoice);

        $this->assertNotEmpty($pdf);
        $this->assertStringContainsString('%PDF', $pdf);
    }

    public function test_can_send_invoice_email(): void
    {
        $invoice = Invoice::factory()->create([
            'booking_id' => $this->booking->id,
            'customer_id' => $this->customer->id,
        ]);

        Mail::fake();

        $this->service->sendInvoiceEmail($invoice);

        Mail::assertSent(\App\Mail\InvoiceMail::class);
    }
}
