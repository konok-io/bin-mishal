<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    
    <style>
        :root {
            --admin-primary: #4F2FE8;
            --admin-primary-dark: #3B21C9;
            --admin-secondary: #0F172A;
            --admin-accent: #F59E0B;
            --admin-bg: #F1F5F9;
            --admin-border: #E2E8F0;
            --admin-text: #334155;
            --admin-sidebar-width: 260px;
            --admin-radius: 10px;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--admin-bg);
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: var(--admin-secondary);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width 0.3s ease;
        }
        
        .sidebar-brand {
            padding: 1.2rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            min-height: 64px;
        }
        
        .sidebar-brand span {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-collapse-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.25);
            cursor: pointer;
            padding: 5px 7px;
            border-radius: 5px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            flex-shrink: 0;
        }
        
        .sidebar-collapse-btn:hover {
            background: var(--admin-primary);
            border-color: transparent;
        }
        
        .admin-sidebar .nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
        }
        
        .admin-sidebar .nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .admin-sidebar .nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .admin-sidebar .nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        .admin-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: #CBD5E1 !important;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            font-size: 0.9rem;
        }
        
        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff !important;
        }
        
        .admin-sidebar .nav-link.active {
            background: var(--admin-primary);
            color: #fff !important;
        }
        
        .admin-sidebar .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .nav-section-title {
            padding: 1rem 1rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748B;
        }
        
        /* Main Content */
        .admin-content {
            margin-left: var(--admin-sidebar-width);
            flex: 1;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        /* Topbar */
        .admin-topbar {
            background: var(--admin-secondary);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-toggle-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.25);
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 6px;
            color: #fff;
            display: none;
        }
        
        .admin-topbar .user-dropdown .dropdown-toggle {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-topbar .dropdown-menu {
            margin-top: 0.5rem;
        }
        
        /* Admin Main */
        .admin-main {
            padding: 1.5rem;
        }
        
        .admin-page-header {
            margin-bottom: 1.5rem;
        }
        
        .admin-page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--admin-secondary);
            margin: 0;
        }
        
        /* Cards */
        .admin-card {
            background: #fff;
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius);
        }
        
        .stat-card {
            background: #fff;
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            height: 100%;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--admin-secondary);
            line-height: 1.2;
        }
        
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #64748B;
        }
        
        /* Mobile */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
            .sidebar-toggle-btn {
                display: flex;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-wrapper">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <span><i class="fas fa-plane me-2"></i>{{ config('app.name') }}</span>
            <button type="button" class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()">
                <i class="fas fa-chevron-left" id="sidebarCollapseIcon"></i>
                <i class="fas fa-chevron-right" id="sidebarExpandIcon" style="display:none"></i>
            </button>
        </div>

        <nav class="nav flex-column pb-4">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge"></i><span>Dashboard</span>
            </a>

            <div class="nav-section-title"><span>CRM</span></div>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Customers</span>
            </a>
            <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <i class="fas fa-user-lines"></i><span>Leads</span>
            </a>

            <div class="nav-section-title"><span>Bookings</span></div>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-ticket"></i><span>Bookings</span>
            </a>
            <a href="{{ route('admin.visas.index') }}" class="nav-link {{ request()->routeIs('admin.visas.*') ? 'active' : '' }}">
                <i class="fas fa-passport"></i><span>Visa Applications</span>
            </a>
            <a href="{{ route('admin.flights.index') }}" class="nav-link {{ request()->routeIs('admin.flights.*') ? 'active' : '' }}">
                <i class="fas fa-plane"></i><span>Flight Requests</span>
            </a>
            <a href="{{ route('admin.umrah.index') }}" class="nav-link {{ request()->routeIs('admin.umrah.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i><span>Umrah Packages</span>
            </a>
            <a href="{{ route('admin.cargo.index') }}" class="nav-link {{ request()->routeIs('admin.cargo.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i><span>Cargo</span>
            </a>

            <div class="nav-section-title"><span>Finance</span></div>
            <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i><span>Invoices</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i><span>Payments</span>
            </a>

            <div class="nav-section-title"><span>HR</span></div>
            <a href="/admin/employees" class="nav-link">
                <i class="fas fa-id-badge"></i><span>Employees</span>
            </a>
            <a href="/admin/leaves" class="nav-link">
                <i class="fas fa-calendar-check"></i><span>Leave Requests</span>
            </a>
            <a href="/admin/attendance" class="nav-link">
                <i class="fas fa-clock"></i><span>Attendance</span>
            </a>
            <a href="/admin/payrolls" class="nav-link">
                <i class="fas fa-wallet"></i><span>Payroll</span>
            </a>
            <a href="/admin/biometric-devices" class="nav-link">
                <i class="fas fa-fingerprint"></i><span>Biometric Devices</span>
            </a>

            <div class="nav-section-title"><span>Accounting</span></div>
            <a href="/admin/chart-of-accounts" class="nav-link">
                <i class="fas fa-university"></i><span>Chart of Accounts</span>
            </a>
            <a href="/admin/ledger-entries" class="nav-link">
                <i class="fas fa-book"></i><span>Ledger Entries</span>
            </a>
            <a href="/admin/expense-claims" class="nav-link">
                <i class="fas fa-receipt"></i><span>Expense Claims</span>
            </a>

            <div class="nav-section-title"><span>Recruitment</span></div>
            <a href="/admin/jobs" class="nav-link">
                <i class="fas fa-briefcase"></i><span>Job Postings</span>
            </a>
            <a href="/admin/job-applications" class="nav-link">
                <i class="fas fa-user-plus"></i><span>Job Applications</span>
            </a>

            <div class="nav-section-title"><span>CMS</span></div>
            <a href="/admin/pages" class="nav-link">
                <i class="fas fa-file-alt"></i><span>Pages</span>
            </a>
            <a href="/admin/menus" class="nav-link">
                <i class="fas fa-list"></i><span>Menus</span>
            </a>
            <a href="/admin/posts" class="nav-link">
                <i class="fas fa-newspaper"></i><span>Blog Posts</span>
            </a>
            <a href="/admin/testimonials" class="nav-link">
                <i class="fas fa-comment"></i><span>Testimonials</span>
            </a>
            <a href="/admin/gallery" class="nav-link">
                <i class="fas fa-images"></i><span>Gallery</span>
            </a>
            <a href="/admin/faqs" class="nav-link">
                <i class="fas fa-question-circle"></i><span>FAQs</span>
            </a>

            <div class="nav-section-title"><span>Support</span></div>
            <a href="/admin/contact-messages" class="nav-link">
                <i class="fas fa-envelope"></i><span>Contact Messages</span>
            </a>
            <a href="/admin/newsletter-subscribers" class="nav-link">
                <i class="fas fa-mailbox"></i><span>Newsletter</span>
            </a>
            <a href="/admin/post-comments" class="nav-link">
                <i class="fas fa-comment-alt"></i><span>Comments</span>
            </a>

            <div class="nav-section-title"><span>Settings</span></div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
            <a href="/admin/seo-settings" class="nav-link">
                <i class="fas fa-search"></i><span>SEO Settings</span>
            </a>
            <a href="/admin/social-links" class="nav-link">
                <i class="fas fa-share-alt"></i><span>Social Links</span>
            </a>
            <a href="/admin/notices" class="nav-link">
                <i class="fas fa-bullhorn"></i><span>Notices</span>
            </a>
            <a href="/admin/translations" class="nav-link">
                <i class="fas fa-language"></i><span>Translations</span>
            </a>
            <a href="/admin/audit-logs" class="nav-link">
                <i class="fas fa-shield-alt"></i><span>Audit Logs</span>
            </a>
        </nav>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="admin-content">
        {{-- Topbar --}}
        <header class="admin-topbar">
            <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-dropdown dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle"></i>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-id-badge me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">@csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-right-from-bracket me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        {{-- Main --}}
        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

{{-- Mobile Overlay --}}
<div class="sidebar-overlay d-none" onclick="toggleSidebar()"></div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('d-none');
}

function toggleSidebarCollapse() {
    const sidebar = document.querySelector('.admin-sidebar');
    const collapseIcon = document.getElementById('sidebarCollapseIcon');
    const expandIcon = document.getElementById('sidebarExpandIcon');
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
            if (collapseIcon) collapseIcon.style.display = 'none';
            if (expandIcon) expandIcon.style.display = 'inline';
            localStorage.setItem('sidebar-collapsed', 'true');
        } else {
            if (collapseIcon) collapseIcon.style.display = 'inline';
            if (expandIcon) expandIcon.style.display = 'none';
            localStorage.setItem('sidebar-collapsed', 'false');
        }
    }
}

// Restore collapsed state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const collapseIcon = document.getElementById('sidebarCollapseIcon');
    const expandIcon = document.getElementById('sidebarExpandIcon');
    if (localStorage.getItem('sidebar-collapsed') === 'true' && sidebar) {
        sidebar.classList.add('collapsed');
        if (collapseIcon) collapseIcon.style.display = 'none';
        if (expandIcon) expandIcon.style.display = 'inline';
    }
});
</script>
@stack('scripts')
</body>
</html>
