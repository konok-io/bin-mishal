<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $moyasarApiKey;
    private string $hyperpayApiKey;
    private string $environment;

    public function __construct()
    {
        $this->moyasarApiKey = config('services.moyasar.secret_key');
        $this->hyperpayApiKey = config('services.hyperpay.api_key');
        $this->environment = config('services.moyasar.environment', 'test');
    }

    /**
     * Process Moyasar payment with idempotency
     */
    public function processMoyasarPayment(float $amount, string $currency, string $description, array $metadata = []): array
    {
        $idempotencyKey = $metadata['idempotency_key'] ?? null;

        // Check for duplicate payment
        if ($idempotencyKey) {
            $existing = Payment::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                Log::info("Duplicate payment attempt blocked", ['idempotency_key' => $idempotencyKey]);
                return [
                    'success' => true,
                    'payment_id' => $existing->id,
                    'status' => $existing->status,
                    'duplicate' => true,
                ];
            }
        }

        try {
            $response = Http::withBasicAuth($this->moyasarApiKey, '')
                ->timeout(30)
                ->post('https://api.moyasar.com/v1/payments', [
                    'amount' => (int) ($amount * 100),
                    'currency' => $currency,
                    'description' => $description,
                    'callback_url' => route('payment.callback'),
                    'metadata' => array_merge($metadata, ['idempotency_key' => $idempotencyKey]),
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_id' => $response['id'],
                    'status' => $response['status'],
                    'action' => $response['action'],
                ];
            }

            return [
                'success' => false,
                'error' => $response['message'] ?? 'Payment failed',
            ];
        } catch (\Exception $e) {
            Log::error('Moyasar payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment service unavailable',
            ];
        }
    }

    /**
     * Handle payment webhook with idempotency
     */
    public function handleWebhook(string $gateway, array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? $payload['id'] ?? null;

        if (!$transactionId) {
            return ['success' => false, 'error' => 'Missing transaction ID'];
        }

        return DB::transaction(function () use ($gateway, $payload, $transactionId) {
            // Lock the payment row to prevent race conditions
            $payment = Payment::where('transaction_id', $transactionId)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                Log::warning("Webhook: Payment not found", ['transaction_id' => $transactionId]);
                return ['success' => false, 'error' => 'Payment not found'];
            }

            // Idempotency check - don't process if already completed
            if ($payment->status === 'completed') {
                Log::info("Webhook: Payment already processed", ['transaction_id' => $transactionId]);
                return ['success' => true, 'status' => 'already_completed'];
            }

            $newStatus = $this->mapGatewayStatus($gateway, $payload);

            if ($newStatus === 'completed') {
                $this->completePaymentInternal($payment, $payload);
            } elseif ($newStatus === 'failed') {
                $payment->update(['status' => 'failed']);
            }

            return ['success' => true, 'status' => $newStatus];
        });
    }

    private function mapGatewayStatus(string $gateway, array $payload): string
    {
        return match ($gateway) {
            'moyasar' => $this->mapMoyasarStatus($payload['status'] ?? ''),
            'hyperpay' => $this->mapHyperpayStatus($payload['result']['code'] ?? ''),
            default => 'pending',
        };
    }

    private function mapMoyasarStatus(string $status): string
    {
        return match ($status) {
            'paid' => 'completed',
            'failed' => 'failed',
            default => 'pending',
        };
    }

    private function mapHyperpayStatus(string $code): string
    {
        return match (true) {
            str_starts_with($code, '000') => 'completed',
            str_starts_with($code, '800') => 'pending',
            default => 'failed',
        };
    }

    private function completePaymentInternal(Payment $payment, array $payload): void
    {
        $gatewayAmount = ($payload['amount'] ?? $payload['amount_paid'] ?? $payment->amount) / 100;

        // Verify amount matches (server-side validation)
        if (abs($gatewayAmount - (float) $payment->amount) > 0.01) {
            Log::error("Payment amount mismatch", [
                'payment_id' => $payment->id,
                'expected' => $payment->amount,
                'received' => $gatewayAmount,
            ]);
            throw new \Exception('Payment amount mismatch');
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update related invoice
        if ($payment->invoice) {
            $invoice = $payment->invoice;
            $invoice->update([
                'paid_amount' => $invoice->paid_amount + $payment->amount,
                'balance' => max(0, $invoice->total - $invoice->paid_amount - $payment->amount),
            ]);

            if ($invoice->balance <= 0) {
                $invoice->update(['status' => 'paid']);
            }
        }

        // Update related booking
        if ($payment->booking) {
            $booking = $payment->booking;
            $booking->addPayment($payment->amount);
        }

        Log::info("Payment completed", ['payment_id' => $payment->id]);
    }

    /**
     * Verify Moyasar payment
     */
    public function verifyMoyasarPayment(string $paymentId): array
    {
        try {
            $response = Http::withBasicAuth($this->moyasarApiKey, '')
                ->timeout(30)
                ->get("https://api.moyasar.com/v1/payments/{$paymentId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response['status'],
                    'amount' => $response['amount'] / 100,
                    'currency' => $response['currency'],
                ];
            }

            return ['success' => false, 'error' => 'Payment not found'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process HyperPay payment
     */
    public function initiateHyperpayPayment(float $amount, string $currency, string $merchantTxId): array
    {
        try {
            $checkoutId = $this->createHyperpayCheckout($amount, $currency, $merchantTxId);

            return [
                'success' => true,
                'checkout_id' => $checkoutId,
                'redirect_url' => $this->getHyperpayRedirectUrl($checkoutId),
            ];
        } catch (\Exception $e) {
            Log::error('HyperPay error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Payment initiation failed'];
        }
    }

    private function createHyperpayCheckout(float $amount, string $currency, string $merchantTxId): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->hyperpayApiKey,
            'Content-Type' => 'application/json',
        ])->post($this->getHyperpayBaseUrl() . '/v1/checkouts', [
            'amount' => $amount,
            'currency' => $currency,
            'merchantTransactionId' => $merchantTxId,
            'paymentTypes' => ['MADA', 'VISA', 'MASTER', 'APPLEPAY', 'STCPAY'],
        ]);

        return $response['id'];
    }

    private function getHyperpayBaseUrl(): string
    {
        return $this->environment === 'live'
            ? 'https://oppwa.com'
            : 'https://test.oppwa.com';
    }

    private function getHyperpayRedirectUrl(string $checkoutId): string
    {
        return $this->getHyperpayBaseUrl() . "/v1/paymentWidgets.js?checkoutId={$checkoutId}";
    }

    /**
     * Record manual payment with idempotency
     */
    public function recordManualPayment(
        int $customerId,
        float $amount,
        string $method,
        ?int $invoiceId = null,
        ?int $bookingId = null,
        ?string $transactionId = null,
        ?string $notes = null,
        ?string $idempotencyKey = null
    ): Payment {
        // Check for duplicate
        if ($idempotencyKey) {
            $existing = Payment::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                return $existing;
            }
        }

        return Payment::create([
            'payment_no' => Payment::generateNo(),
            'customer_id' => $customerId,
            'invoice_id' => $invoiceId,
            'booking_id' => $bookingId,
            'amount' => $amount,
            'currency' => 'SAR',
            'method' => $method,
            'transaction_id' => $transactionId,
            'idempotency_key' => $idempotencyKey,
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, float $amount, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($payment, $amount, $reason) {
            // Lock row
            $payment = Payment::lockForUpdate()->find($payment->id);

            if ($payment->status !== 'completed') {
                return false;
            }

            if ($amount > $payment->amount) {
                return false;
            }

            // Create refund record
            $refund = Payment::create([
                'payment_no' => Payment::generateNo(),
                'customer_id' => $payment->customer_id,
                'invoice_id' => $payment->invoice_id,
                'booking_id' => $payment->booking_id,
                'amount' => $amount,
                'currency' => $payment->currency,
                'method' => 'refund_' . $payment->method,
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => "Refund of payment #{$payment->payment_no}. Reason: {$reason}",
                'created_by' => auth()->id(),
            ]);

            // Mark original as refunded
            $payment->update(['status' => 'refunded']);

            // Update related invoice
            if ($payment->invoice) {
                $payment->invoice->update([
                    'paid_amount' => max(0, $payment->invoice->paid_amount - $amount),
                    'balance' => $payment->invoice->balance + $amount,
                ]);
            }

            // Update related booking
            if ($payment->booking) {
                $booking = $payment->booking;
                $booking->update([
                    'paid_amount' => max(0, $booking->paid_amount - $amount),
                    'due_amount' => $booking->due_amount + $amount,
                ]);
                $booking->updatePaymentStatus();
            }

            return true;
        });
    }

    /**
     * Generate payment receipt
     */
    public function generateReceipt(Payment $payment): array
    {
        return [
            'receipt_no' => $payment->payment_no,
            'date' => $payment->created_at->format('d M Y H:i'),
            'customer' => $payment->customer?->user?->name ?? 'N/A',
            'amount' => number_format($payment->amount, 2),
            'currency' => $payment->currency,
            'method' => ucfirst(str_replace('_', ' ', $payment->method)),
            'status' => $payment->status,
            'transaction_id' => $payment->transaction_id,
        ];
    }

    /**
     * Complete a payment (for admin interface)
     */
    public function completePayment(Payment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            $payment = Payment::lockForUpdate()->find($payment->id);

            if ($payment->status !== 'pending') {
                return false;
            }

            $this->completePaymentInternal($payment, ['amount' => $payment->amount * 100]);

            return true;
        });
    }
}
