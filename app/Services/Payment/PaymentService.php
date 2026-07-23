<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
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
     * Process Moyasar payment
     */
    public function processMoyasarPayment(float $amount, string $currency, string $description, array $metadata = []): array
    {
        try {
            $response = Http::withBasicAuth($this->moyasarApiKey, '')
                ->post('https://api.moyasar.com/v1/payments', [
                    'amount' => (int) ($amount * 100), // Convert to halalah
                    'currency' => $currency,
                    'description' => $description,
                    'callback_url' => route('payment.callback'),
                    'metadata' => $metadata,
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
     * Verify Moyasar payment
     */
    public function verifyMoyasarPayment(string $paymentId): array
    {
        try {
            $response = Http::withBasicAuth($this->moyasarApiKey, '')
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
     * Record manual payment
     */
    public function recordManualPayment(
        int $customerId,
        float $amount,
        string $method,
        ?int $invoiceId = null,
        ?int $bookingId = null,
        ?string $transactionId = null,
        ?string $notes = null
    ): Payment {
        $payment = Payment::create([
            'payment_no' => Payment::generateNo(),
            'customer_id' => $customerId,
            'invoice_id' => $invoiceId,
            'booking_id' => $bookingId,
            'amount' => $amount,
            'currency' => 'SAR',
            'method' => $method,
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);

        return $payment;
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, float $amount, ?string $reason = null): bool
    {
        if ($payment->status !== 'completed') {
            return false;
        }

        if ($amount > $payment->amount) {
            return false;
        }

        // Process refund based on original payment method
        // For now, just mark as refunded
        $payment->update([
            'status' => 'refunded',
            'notes' => $payment->notes . "\nRefund: {$reason}",
        ]);

        // Update related invoice/booking
        if ($payment->invoice) {
            $payment->invoice->update([
                'paid_amount' => $payment->invoice->paid_amount - $amount,
                'balance' => $payment->invoice->balance + $amount,
            ]);
        }

        return true;
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
}
