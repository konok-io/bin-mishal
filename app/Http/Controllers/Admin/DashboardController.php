<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BiometricAttendance;
use App\Models\BiometricDevice;
use App\Models\ChartOfAccount;
use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\ExpenseClaim;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\LedgerEntry;
use App\Models\Leave;
use App\Models\Lead;
use App\Models\NewsletterSubscriber;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\PostComment;
use App\Models\User;
use App\Models\VisaApplication;
use App\Models\Cargo\Cargo;
use App\Models\InvestorApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Date range filter (default: last 30 days)
        $dateRange = request('date_range', '30days');
        $startDate = match($dateRange) {
            'today' => now()->startOfDay(),
            '7days' => now()->subDays(7)->startOfDay(),
            '30days' => now()->subDays(30)->startOfDay(),
            'this_month' => now()->startOfMonth(),
            default => now()->subDays(30)->startOfDay(),
        };

        $stats = [
            // Customers
            'customers' => [
                'total' => Customer::count(),
                'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            ],
            
            // Bookings
            'bookings' => [
                'total' => Booking::count(),
                'pending' => Booking::where('booking_status', 'pending')->count(),
                'issued' => Booking::where('booking_status', 'issued')->count(),
                'total_revenue' => Booking::where('booking_status', 'issued')->sum('total_amount'),
                'recent_count' => Booking::where('created_at', '>=', $startDate)->count(),
            ],
            
            // Visas
            'visas' => [
                'total' => VisaApplication::count(),
                'pending' => VisaApplication::pending()->count(),
                'approved' => VisaApplication::where('status', 'approved')->count(),
            ],
            
            // Leads
            'leads' => [
                'total' => Lead::count(),
                'new' => Lead::where('status', 'new')->count(),
                'converted' => Lead::where('status', 'converted')->count(),
                'due_today' => Lead::dueToday()->count(),
            ],
            
            // Payments
            'payments' => [
                'total_received' => Payment::where('status', 'completed')->sum('amount'),
                'pending' => Payment::where('status', 'pending')->count(),
            ],
            
            // Cargo Module (Phase 4)
            'cargo' => [
                'total' => Cargo::count(),
                'pending' => Cargo::whereIn('status', ['booked', 'processing'])->count(),
                'in_transit' => Cargo::where('status', 'in_transit')->count(),
                'delivered' => Cargo::where('status', 'delivered')->count(),
                'total_revenue' => Cargo::where('status', 'delivered')->sum('total_amount'),
            ],
            
            // Investment Module (Phase 5)
            'investments' => [
                'total' => InvestorApplication::count(),
                'pending' => InvestorApplication::where('status', 'submitted')->count(),
                'under_review' => InvestorApplication::where('status', 'under_review')->count(),
                'approved' => InvestorApplication::where('status', 'approved')->count(),
            ],
            
            // Careers Module (Phase 8)
            'careers' => [
                'open_positions' => Job::where('status', 'published')->where('deadline', '>=', now())->count(),
                'total_applications' => JobApplication::count(),
                'new_applications' => JobApplication::where('created_at', '>=', $startDate)->count(),
            ],
            
            // Contact Messages (Phase 1)
            'messages' => [
                'total' => ContactMessage::count(),
                'unread' => ContactMessage::where('is_read', false)->count(),
            ],
            
            // HR/Payroll (Phase 10)
            'hr' => [
                'total_employees' => Employee::count(),
                'active_employees' => Employee::where('status', 'active')->count(),
                'today_present' => BiometricAttendance::whereDate('punch_date', now()->toDateString())
                    ->whereNotNull('check_in_time')
                    ->distinct('employee_id')
                    ->count('employee_id'),
                'today_absent' => Employee::where('status', 'active')
                    ->whereDoesntHave('attendances', fn($q) => $q->whereDate('punch_date', now()->toDateString()))
                    ->count(),
                'pending_leave' => Leave::where('status', 'pending')->count(),
                'pending_payroll' => Payroll::where('status', 'draft')->count(),
            ],
            
            // Expense Claims (Phase 13)
            'expenses' => [
                'pending_claims' => ExpenseClaim::where('status', 'submitted')->count(),
                'total_claimed' => ExpenseClaim::where('status', 'submitted')->sum('amount'),
            ],
            
            // Accounting (Phase 14)
            'accounting' => [
                'total_income' => LedgerEntry::where('type', 'income')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'total_expense' => LedgerEntry::where('type', 'expense')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'net_profit' => LedgerEntry::where('created_at', '>=', $startDate)
                    ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE -amount END) as net')
                    ->value('net') ?? 0,
            ],
            
            // Blog/Content (Phase 7)
            'content' => [
                'pending_comments' => PostComment::where('is_approved', false)->count(),
                'newsletter_subscribers' => NewsletterSubscriber::where('status', 'active')->count(),
                'new_subscribers' => NewsletterSubscriber::where('created_at', '>=', $startDate)->count(),
            ],
            
            // Biometric Devices (Phase 12)
            'biometric' => [
                'total_devices' => BiometricDevice::count(),
                'online_devices' => BiometricDevice::where('status', 'active')->count(),
                'offline_devices' => BiometricDevice::where('status', '!=', 'active')->count(),
            ],
        ];

        // Recent Bookings
        $recentBookings = Booking::with(['customer.user'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with(['customer.user'])
            ->where('status', 'completed')
            ->latest('paid_at')
            ->limit(5)
            ->get();

        // Recent Cargo Shipments
        $recentCargo = Cargo::with(['customer.user'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent Messages
        $recentMessages = ContactMessage::latest()
            ->limit(5)
            ->get();

        // Pending Approvals Summary
        $pendingApprovals = [
            'leave_requests' => Leave::where('status', 'pending')->count(),
            'expense_claims' => ExpenseClaim::where('status', 'submitted')->count(),
            'payroll_batches' => Payroll::where('status', 'draft')->count(),
            'pending_comments' => PostComment::where('is_approved', false)->count(),
            'job_applications' => JobApplication::where('status', 'received')->count(),
        ];
        $totalPendingApprovals = array_sum($pendingApprovals);

        // Top Services by Bookings
        $topServices = Booking::select('service_type')
            ->where('created_at', '>=', $startDate)
            ->groupBy('service_type')
            ->selectRaw('COUNT(*) as count, service_type')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Revenue by Service Type
        $revenueByService = Booking::where('booking_status', 'issued')
            ->where('created_at', '>=', $startDate)
            ->select('service_type')
            ->selectRaw('SUM(total_amount) as total')
            ->groupBy('service_type')
            ->get()
            ->pluck('total', 'service_type');

        return view('admin.dashboard.index', compact(
            'stats', 
            'recentBookings', 
            'recentPayments',
            'recentCargo',
            'recentMessages',
            'pendingApprovals',
            'totalPendingApprovals',
            'topServices',
            'revenueByService',
            'dateRange'
        ));
    }
}
