<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $service;
    private Customer $customer;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
        
        $this->customer = Customer::factory()->create();
        $this->booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'total_amount' => 1000,
            'paid_amount' => 0,
        ]);
    }

    public function test_can_create_payment(): void
    {
        $payment = $this->service->createPayment([
            'customer_id' => $this->customer->id,
            'booking_id' => $this->booking->id,
            'amount' => 500,
            'payment_method' => 'bank_transfer',
            'reference' => 'REF123',
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount' => 500,
            'status' => 'pending',
        ]);
    }

    public function test_can_complete_payment(): void
    {
        $payment = Payment::factory()->create([
            'customer_id' => $this->customer->id,
            'booking_id' => $this->booking->id,
            'amount' => 500,
            'status' => 'pending',
        ]);

        $result = $this->service->completePayment($payment);

        $this->assertTrue($result);
        $this->assertEquals('completed', $payment->fresh()->status);
    }

    public function test_payment_updates_booking_paid_amount(): void
    {
        $payment = Payment::factory()->create([
            'customer_id' => $this->customer->id,
            'booking_id' => $this->booking->id,
            'amount' => 500,
            'status' => 'pending',
        ]);

        $this->service->completePayment($payment);

        $this->assertEquals(500, $this->booking->fresh()->paid_amount);
    }

    public function test_can_process_refund(): void
    {
        $payment = Payment::factory()->create([
            'customer_id' => $this->customer->id,
            'booking_id' => $this->booking->id,
            'amount' => 500,
            'status' => 'completed',
        ]);

        $result = $this->service->refundPayment($payment, 'Customer request');

        $this->assertTrue($result);
        $this->assertEquals('refunded', $payment->fresh()->status);
    }

    public function test_generates_transaction_id(): void
    {
        $payment = Payment::factory()->create([
            'customer_id' => $this->customer->id,
            'booking_id' => $this->booking->id,
            'amount' => 500,
        ]);

        $this->assertNotNull($payment->transaction_id);
        $this->assertStringStartsWith('TXN-', $payment->transaction_id);
    }
}
