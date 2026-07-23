<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Payment::with(['customer.user', 'createdBy']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('method')) {
            $query->where('method', $request->method);
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($payments);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'method' => 'required|in:bank_transfer,credit_card,debit_card,cash,check,sadad,mada,apple_pay,wallet',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'payment_no' => Payment::generateNo(),
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'SAR',
                'method' => $request->method,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
                'status' => 'pending',
                'created_by' => $request->user()->id,
            ]);

            DB::commit();

            return $this->success($payment->load('customer.user'), 'Payment created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create payment: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $payment = Payment::with(['customer.user', 'createdBy', 'verifiedBy', 'invoice', 'booking'])->findOrFail($id);

        return $this->success($payment);
    }

    public function complete(int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return $this->error('Payment is not in pending status');
        }

        $payment->complete();

        return $this->success($payment->load('customer'), 'Payment completed successfully');
    }

    public function verify(int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->verify($payment->createdBy);

        return $this->success($payment->load('verifiedBy'), 'Payment verified successfully');
    }

    public function fail(int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return $this->error('Cannot fail a completed payment');
        }

        $payment->fail();

        return $this->success($payment, 'Payment marked as failed');
    }

    public function refund(int $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'completed') {
            return $this->error('Can only refund completed payments');
        }

        $payment->refund();

        return $this->success($payment, 'Payment refunded successfully');
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'refunded' => Payment::where('status', 'refunded')->count(),
            'total_received' => Payment::where('status', 'completed')->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->sum('amount'),
            'total_refunded' => Payment::where('status', 'refunded')->sum('amount'),
        ];

        return $this->success($stats);
    }

    public function byMethod(): JsonResponse
    {
        $byMethod = Payment::where('status', 'completed')
            ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        return $this->success($byMethod);
    }
}
