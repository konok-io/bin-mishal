<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Cargo\Cargo;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\InvestorApplication;
use App\Models\Content\Comment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AdminNotificationCenter extends Widget
{
    protected static bool $isDiscovered = false;
    
    protected int|string|array $columnSpan = 2;
    
    public static function canView(): bool
    {
        return true;
    }
    
    public function getNotifications(): array
    {
        $notifications = [];
        $user = Auth::user();
        
        // New Bookings (last 24 hours)
        $newBookings = Booking::where('created_at', '>=', now()->subDay())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        
        if ($newBookings > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-ticket',
                'icon_color' => 'text-primary',
                'title' => 'New Bookings',
                'count' => $newBookings,
                'url' => '/admin/bookings',
                'time' => 'Last 24 hours',
            ];
        }
        
        // Pending Bookings
        $pendingBookings = Booking::where('status', 'pending')->count();
        if ($pendingBookings > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-clock',
                'icon_color' => 'text-warning',
                'title' => 'Pending Bookings',
                'count' => $pendingBookings,
                'url' => '/admin/bookings?tableFilters[status][value]=pending',
                'time' => 'Needs attention',
            ];
        }
        
        // New Cargo Requests
        $newCargo = Cargo::where('created_at', '>=', now()->subDay())
            ->count();
        
        if ($newCargo > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-truck',
                'icon_color' => 'text-info',
                'title' => 'New Cargo',
                'count' => $newCargo,
                'url' => '/admin/cargo',
                'time' => 'Last 24 hours',
            ];
        }
        
        // Pending Cargo
        $pendingCargo = Cargo::whereIn('status', ['booked', 'pending'])->count();
        if ($pendingCargo > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-truck',
                'icon_color' => 'text-warning',
                'title' => 'Pending Cargo',
                'count' => $pendingCargo,
                'url' => '/admin/cargo/index',
                'time' => 'Needs processing',
            ];
        }
        
        // Unread Contact Messages
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        if ($unreadMessages > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-envelope',
                'icon_color' => 'text-success',
                'title' => 'Unread Messages',
                'count' => $unreadMessages,
                'url' => '/admin/inbox',
                'time' => 'Needs response',
            ];
        }
        
        // New Job Applications
        $newApplications = JobApplication::where('created_at', '>=', now()->subDay())
            ->count();
        
        if ($newApplications > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-user-group',
                'icon_color' => 'text-primary',
                'title' => 'New Applications',
                'count' => $newApplications,
                'url' => '/admin/job-applications',
                'time' => 'Last 24 hours',
            ];
        }
        
        // Pending Job Applications
        $pendingApplications = JobApplication::where('status', 'pending')->count();
        if ($pendingApplications > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-user-group',
                'icon_color' => 'text-warning',
                'title' => 'Pending Applications',
                'count' => $pendingApplications,
                'url' => '/admin/job-applications?tableFilters[status][value]=pending',
                'time' => 'Needs review',
            ];
        }
        
        // New Investor Applications
        $newInvestorApps = InvestorApplication::where('created_at', '>=', now()->subDay())
            ->count();
        
        if ($newInvestorApps > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-briefcase',
                'icon_color' => 'text-success',
                'title' => 'Investor Inquiries',
                'count' => $newInvestorApps,
                'url' => '/admin/investor-applications',
                'time' => 'Last 24 hours',
            ];
        }
        
        // Pending Comments (if comment system exists)
        if (class_exists(\App\Models\Content\Comment::class)) {
            $pendingComments = Comment::where('is_approved', false)->count();
            if ($pendingComments > 0) {
                $notifications[] = [
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                    'icon_color' => 'text-secondary',
                    'title' => 'Pending Comments',
                    'count' => $pendingComments,
                    'url' => '/admin/comments',
                    'time' => 'Needs moderation',
                ];
            }
        }
        
        // Pending Investor Applications
        $pendingInvestorApps = InvestorApplication::whereIn('status', ['submitted', 'under_review'])->count();
        if ($pendingInvestorApps > 0) {
            $notifications[] = [
                'icon' => 'heroicon-o-briefcase',
                'icon_color' => 'text-warning',
                'title' => 'Investor Applications',
                'count' => $pendingInvestorApps,
                'url' => '/admin/investor-applications',
                'time' => 'Needs review',
            ];
        }
        
        return $notifications;
    }
    
    public function getTotalCount(): int
    {
        return array_sum(array_column($this->getNotifications(), 'count'));
    }
}
