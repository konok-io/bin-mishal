<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white sidebar flex-shrink-0" style="width: 250px; min-height: 100vh;">
            <div class="p-3 border-bottom border-secondary">
                <h5 class="mb-0">
                    <i class="bi bi-airplane"></i> {{ config('app.name') }}
                </h5>
                <small class="text-muted">Admin Panel</small>
            </div>
            <ul class="nav flex-column py-2">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.customers.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.customers.index') }}">
                        <i class="bi bi-people me-2"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.bookings.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.bookings.index') }}">
                        <i class="bi bi-ticket-perforated me-2"></i> Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.visas.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.visas.index') }}">
                        <i class="bi bi-passport me-2"></i> Visa Applications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.flights.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.flights.index') }}">
                        <i class="bi bi-airplane me-2"></i> Flight Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.umrah.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.umrah.index') }}">
                        <i class="bi bi-building me-2"></i> Umrah Packages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.leads.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.leads.index') }}">
                        <i class="bi bi-person-lines-fill me-2"></i> Leads
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.invoices.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.invoices.index') }}">
                        <i class="bi bi-receipt me-2"></i> Invoices
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.payments.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="bi bi-credit-card me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">@csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
