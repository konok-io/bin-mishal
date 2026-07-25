<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Admin — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" id="bs-ltr">
    <script>
      (function(){try{
        var m=document.cookie.match(/googtrans=\/[^\/]+\/([a-z-]+)/);var l=m?m[1]:'';
        var rtl=['ar','ur','fa','he','ps','sd'];
        if(rtl.indexOf(l)>=0){var ltr=document.getElementById('bs-ltr');if(ltr) ltr.href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.rtl.min.css';}
      }catch(e){}})();
    </script>
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- DataTables with Bootstrap 5 styling --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <script>
      // Apply saved theme before render (default: light).
      (function(){try{var t=localStorage.getItem('pc-theme')||'light';if(t==='dark')document.documentElement.setAttribute('data-theme','dark');}catch(e){}})();
      (function(){
        try{
          var m=document.cookie.match(/googtrans=\/[^\/]+\/([a-z-]+)/);
          var l=m?m[1]:'en';
          var rtl=['ar','ur','fa','he','ps','sd'];
          var isRtl=rtl.indexOf(l)>=0;
          document.documentElement.setAttribute('dir',isRtl?'rtl':'ltr');
          // Apply language-specific font classes
          var body=document.body;
          body.classList.remove('TEWGTB-BANGLA','TEWGTB-ARABIC','TEWGTB-URDU','TEWGTB-HINDI','TEWGTB-FILIPINO');
          if(l==='bn'||l==='bengali')body.classList.add('TEWGTB-BANGLA');
          else if(l==='ar'||l==='arabic')body.classList.add('TEWGTB-ARABIC');
          else if(l==='ur'||l==='urdu')body.classList.add('TEWGTB-URDU');
          else if(l==='hi'||l==='hindi')body.classList.add('TEWGTB-HINDI');
          else if(l==='tl'||l==='filipino')body.classList.add('TEWGTB-FILIPINO');
          // RTL: flip collapse icons
          if(isRtl){
            var collapseIcon=document.getElementById('sidebarCollapseIcon');
            var expandIcon=document.getElementById('sidebarExpandIcon');
            if(collapseIcon){collapseIcon.classList.remove('fa-chevron-left');collapseIcon.classList.add('fa-chevron-right');}
            if(expandIcon){expandIcon.classList.remove('fa-chevron-right');expandIcon.classList.add('fa-chevron-left');}
          }
        }catch(e){}
      })();
    </script>
    <style>
      /* language switch + theme toggle */
      .gtranslate-wrap{position:relative}
      .gt-btn,.theme-toggle-btn{display:inline-flex;align-items:center;gap:7px;font-size:.9rem;font-weight:500;color:#333;background:#fff;border:1px solid #e2e2e8;border-radius:20px;padding:7px 14px;cursor:pointer;transition:border-color .18s,background .18s}
      .theme-toggle-btn{width:38px;height:38px;justify-content:center;border-radius:50%;padding:0}
      .gt-btn:hover,.theme-toggle-btn:hover{border-color:#4F2FE8}
      .lang-menu{position:absolute;top:54px;right:0;z-index:1050;background:#fff;border:1px solid #e2e2e8;border-radius:12px;padding:6px;display:none;box-shadow:0 24px 60px -30px rgba(0,0,0,.3);max-height:360px;overflow:auto;min-width:170px}
      body.gt-open .lang-menu{display:block}
      .lang-menu button{display:block;width:100%;text-align:left;background:none;border:0;color:#333;font-size:14.5px;padding:9px 14px;border-radius:8px;cursor:pointer}
      .lang-menu button:hover{background:#f4f4f9}
      [data-theme="dark"] .lang-menu{background:#171433;border-color:#2C2860}
      [data-theme="dark"] .lang-menu button,[data-theme="dark"] .admin-topbar .lang-menu button{color:#fff!important}
      [data-theme="dark"] .lang-menu button:hover{background:#1E1A44}
      [data-theme="dark"] .admin-topbar .gt-btn,[data-theme="dark"] .admin-topbar .theme-toggle-btn{color:#fff}
      .goog-te-banner-frame,.skiptranslate iframe{display:none!important}
      body{top:0!important}
      font font{background:transparent!important;box-shadow:none!important}
      /* Topbar matches sidebar header */
      .admin-topbar{background:linear-gradient(160deg, #0F172A 0%, #1a1f4a 50%, #0F172A 100%);color:#fff;position:sticky;top:0;z-index:1000;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
      .admin-topbar .text-dark{color:#fff!important}
      .admin-topbar .gt-btn,.admin-topbar .theme-toggle-btn{background:rgba(255,255,255,0.1);color:#fff;border-color:rgba(255,255,255,0.2)}
      .admin-topbar .gt-btn:hover,.admin-topbar .theme-toggle-btn:hover{background:rgba(255,255,255,0.2);border-color:rgba(255,255,255,0.4)}
      .admin-topbar .sidebar-toggle-btn{color:#fff}
      .admin-topbar .lang-menu button{color:#fff}
      .admin-topbar .dropdown-menu{margin-top:8px;top:15px!important}
      .admin-topbar .gtranslate-wrap{position:relative}
      /* Page header padding */
      .admin-page-header{padding:10px;border-radius:8px}
      /* Card padding */
      .admin-card{padding:10px}
      /* dark theme */
      [data-theme="dark"] body,[data-theme="dark"] .admin-body,[data-theme="dark"] .admin-wrapper,[data-theme="dark"] .admin-main,[data-theme="dark"] .admin-content{background:#0A0A1F!important;color:#EDECFF}
      [data-theme="dark"] .admin-topbar,[data-theme="dark"] .admin-sidebar,[data-theme="dark"] .card,[data-theme="dark"] .admin-card,[data-theme="dark"] .stat-card,[data-theme="dark"] .dropdown-menu,[data-theme="dark"] .admin-page-header{background:linear-gradient(160deg, #0F172A 0%, #1a1f4a 50%, #0F172A 100%)!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .gt-btn,[data-theme="dark"] .theme-toggle-btn{background:#171433;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] .text-dark,[data-theme="dark"] .text-body,[data-theme="dark"] .stat-value,[data-theme="dark"] h1,[data-theme="dark"] h2,[data-theme="dark"] h3,[data-theme="dark"] h4,[data-theme="dark"] h5{color:#EDECFF!important}
      [data-theme="dark"] .text-muted,[data-theme="dark"] .stat-label,[data-theme="dark"] .admin-breadcrumb,[data-theme="dark"] .admin-breadcrumb a,[data-theme="dark"] small{color:#9B98C7!important}
      [data-theme="dark"] .table{color:#EDECFF!important;border-color:#2C2860;--bs-table-bg:#171433;--bs-table-color:#EDECFF;--bs-table-striped-bg:#1B1740;--bs-table-striped-color:#EDECFF;--bs-table-hover-bg:#1E1A44;--bs-table-hover-color:#EDECFF;--bs-table-border-color:#2C2860}
      [data-theme="dark"] .table thead th{background:#12102E!important;color:#EDECFF!important;border-color:#2C2860}
      [data-theme="dark"] .table tbody td,[data-theme="dark"] .table tbody th,[data-theme="dark"] .table tbody tr{background:#171433!important;color:#EDECFF!important;border-color:#2C2860}
      [data-theme="dark"] .table td,[data-theme="dark"] .table th{border-color:#2C2860}
      [data-theme="dark"] .table-hover tbody tr:hover td{background:#1E1A44!important;color:#EDECFF}
      [data-theme="dark"] .table-admin thead th{background:#12102E!important;color:#9B98C7!important;border-color:#2C2860!important}
      [data-theme="dark"] .table-admin td,[data-theme="dark"] .table-admin tr{background:#171433!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .card-header-custom,[data-theme="dark"] .card-body-custom{background:#171433!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .rounded-3.bg-light,[data-theme="dark"] .border.bg-light{background:#1E1A44!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .form-control,[data-theme="dark"] .form-select,[data-theme="dark"] textarea{background:#0A0A1F;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] .form-control::placeholder{color:#6B6790}
      [data-theme="dark"] .dropdown-item{color:#EDECFF}
      [data-theme="dark"] .dropdown-item:hover{background:#1E1A44}
      [data-theme="dark"] .border,[data-theme="dark"] .border-top,[data-theme="dark"] .border-bottom,[data-theme="dark"] hr{border-color:#2C2860!important}
      [data-theme="dark"] .bg-light,[data-theme="dark"] .bg-white{background:#171433!important}
      [data-theme="dark"] .btn-light,[data-theme="dark"] .btn-outline-secondary{background:#1E1A44;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] a:not(.btn):not(.nav-link){color:#22D3EE}
      [data-theme="dark"] .dataTables_wrapper{color:#EDECFF}
      [data-theme="dark"] table.dataTable,[data-theme="dark"] .dataTable tbody td,[data-theme="dark"] .dataTable thead th{background:#171433!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .dataTable tbody tr{background:#171433!important}
      [data-theme="dark"] .dataTables_wrapper .dataTables_length select,[data-theme="dark"] .dataTables_wrapper .dataTables_filter input{background:#0A0A1F;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] .page-link{background:#171433;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] .page-item.disabled .page-link{background:#12102E;color:#6B6790}
      [data-theme="dark"] .ProseMirror,[data-theme="dark"] .ql-editor,[data-theme="dark"] .editor-content,[data-theme="dark"] [contenteditable]{background:#0A0A1F!important;color:#EDECFF!important}
      [data-theme="dark"] .ql-toolbar,[data-theme="dark"] .editor-toolbar,[data-theme="dark"] .tox-toolbar{background:#171433!important;border-color:#2C2860!important}
      [data-theme="dark"] .editor-toolbar button,[data-theme="dark"] .ql-toolbar button,[data-theme="dark"] .ql-toolbar .ql-stroke{color:#EDECFF!important;stroke:#EDECFF!important}
      [data-theme="dark"] .modal-content,[data-theme="dark"] .offcanvas,[data-theme="dark"] .list-group-item,[data-theme="dark"] .input-group-text,[data-theme="dark"] .accordion-button,[data-theme="dark"] .accordion-body{background:#171433!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .nav-tabs .nav-link{color:#9B98C7}
      [data-theme="dark"] .nav-tabs .nav-link.active{background:#171433;color:#EDECFF;border-color:#2C2860}
      [data-theme="dark"] .badge.bg-light{background:#1E1A44!important;color:#EDECFF!important}
      [data-theme="dark"] .btn-primary{background:#4F2FE8!important;color:#fff!important;border-color:#4F2FE8!important}
      [data-theme="dark"] .btn-primary:hover{background:#7C3AED!important;color:#fff!important}
      [data-theme="dark"] .btn-secondary{background:#3A356E!important;color:#fff!important;border-color:#3A356E!important}
      [data-theme="dark"] .btn-success{background:#16a34a!important;color:#fff!important;border-color:#16a34a!important}
      [data-theme="dark"] .btn-danger{background:#dc2626!important;color:#fff!important;border-color:#dc2626!important}
      [data-theme="dark"] .btn-warning{background:#d97706!important;color:#fff!important;border-color:#d97706!important}
      [data-theme="dark"] .btn-info{background:#0891B2!important;color:#fff!important;border-color:#0891B2!important}
      [data-theme="dark"] .btn-light{background:#1E1A44!important;color:#EDECFF!important;border-color:#2C2860!important}
      [data-theme="dark"] .btn-dark{background:#1E1A44!important;color:#fff!important;border-color:#2C2860!important}
      [data-theme="dark"] .btn-outline-primary,[data-theme="dark"] .btn-outline-secondary,[data-theme="dark"] .btn-outline-light,[data-theme="dark"] .btn-outline-dark{color:#EDECFF!important;border-color:#4F2FE8!important;background:transparent!important}
      [data-theme="dark"] .btn-outline-primary:hover,[data-theme="dark"] .btn-outline-secondary:hover,[data-theme="dark"] .btn-outline-light:hover,[data-theme="dark"] .btn-outline-dark:hover{background:#4F2FE8!important;color:#fff!important}
      [data-theme="dark"] .btn-outline-danger{color:#f87171!important;border-color:#dc2626!important}
      [data-theme="dark"] .btn-outline-danger:hover{background:#dc2626!important;color:#fff!important}
      [data-theme="dark"] .btn-link{color:#22D3EE!important}
      [data-theme="dark"] .btn-close{filter:invert(1) grayscale(100%) brightness(200%)}
      [data-theme="dark"] .admin-sidebar .nav-link{color:#9B98C7}
      [data-theme="dark"] .admin-sidebar .nav-link:hover{color:#fff;background:rgba(224, 85, 34, 0.25)}
      [data-theme="dark"] .admin-sidebar .nav-link:hover i{color:#E05522}
      [data-theme="dark"] .admin-sidebar .nav-link.active{color:#fff;background:linear-gradient(135deg, rgba(224, 85, 34, 0.3) 0%, rgba(15, 23, 42, 0.9) 100%);border-left:3px solid #E05522}
      [data-theme="dark"] .admin-sidebar .nav-link.active i{color:#E05522}
      /* Sidebar collapse styles */
      .admin-sidebar.collapsed{width:70px!important}
      .admin-sidebar.collapsed .sidebar-brand span,
      .admin-sidebar.collapsed .nav-section-title > span,
      .admin-sidebar.collapsed .nav-link > span,
      .admin-sidebar.collapsed .badge.ms-auto{display:none!important}
      .admin-sidebar .sidebar-brand{padding:1rem 0.5rem 1rem 1rem;padding-right:0!important}
      .admin-sidebar .site-logo-icon{font-size:1.2rem;color:#E05522}
      .admin-sidebar.collapsed .sidebar-brand{justify-content:center;padding:0!important}
      .admin-sidebar.collapsed .sidebar-brand > div:first-child{display:flex!important;padding-left:10px!important;align-items:center}
      .admin-sidebar.collapsed .sidebar-brand > div:first-child span{display:none!important}
      /* RTL: Use padding-right for RTL languages */
      [dir="rtl"] .admin-sidebar.collapsed .sidebar-brand > div:first-child{padding-left:0!important;padding-right:0.5rem!important}
      [dir="rtl"] .admin-sidebar.collapsed .sidebar-brand{padding-right:0!important}
      /* RTL sidebar brand padding when expanded */
      [dir="rtl"] .admin-sidebar .sidebar-brand{padding-left:0!important;padding-right:1rem!important}
      .admin-sidebar.collapsed .sidebar-collapse-btn{
        margin:0;position:static;transform:none;display:flex!important;align-items:center;justify-content:center
      }
      .admin-sidebar.collapsed .nav-link i{font-size:1.1rem}
      .admin-sidebar.collapsed .nav{
        overflow-y:auto;
        -ms-overflow-style:none;
        scrollbar-width:none;
      }
      .admin-sidebar.collapsed .nav::-webkit-scrollbar{display:none}
      
      /* RTL: Flip collapse/expand icons for RTL languages */
      [dir="rtl"] #sidebarCollapseIcon,
      body.TEWGTB-ARABIC #sidebarCollapseIcon,
      body.TEWGTB-URDU #sidebarCollapseIcon {
        transform: scaleX(-1);
      }
      [dir="rtl"] #sidebarExpandIcon,
      body.TEWGTB-ARABIC #sidebarExpandIcon,
      body.TEWGTB-URDU #sidebarExpandIcon {
        transform: scaleX(-1);
      }
      /* Mobile sidebar header */
      @media (max-width: 991.98px){
        .admin-sidebar .sidebar-brand{padding:1rem}
        .admin-sidebar .sidebar-brand > div:first-child{display:flex!important;align-items:center;gap:0.75rem}
        .admin-sidebar .sidebar-brand .sidebar-collapse-btn{display:flex!important;position:static;transform:none}
      }
      .sidebar-collapse-btn{
        background:transparent;
        border:1px solid rgba(255,255,255,0.25);
        cursor:pointer;
        padding:5px 7px;
        border-radius:5px;
        transition:all .25s ease;
        display:flex;
        align-items:center;
        justify-content:center;
        min-width:26px;
        min-height:26px;
      }
      .sidebar-collapse-btn i{color:#fff!important;font-size:11px!important}
      .sidebar-collapse-btn:hover{
        background:rgba(79,47,232,0.9);
        border-color:transparent
      }
      .sidebar-collapse-btn:hover i{color:#fff!important}
      .sidebar-collapse-btn:active{transform:scale(0.95)}
      [data-theme="dark"] .sidebar-collapse-btn{
        background:transparent;
        border-color:rgba(255,255,255,0.2)
      }
      [data-theme="dark"] .sidebar-collapse-btn i{color:#fff!important}
      .admin-sidebar.collapsed ~ .admin-content{margin-left:70px!important}
      /* RTL support */
      [dir="rtl"] .admin-sidebar{left:auto;right:0}
      [dir="rtl"] .admin-sidebar.collapsed{width:70px!important}
      [dir="rtl"] .admin-sidebar.collapsed ~ .admin-content{margin-left:0!important;margin-right:70px!important}
      /* Language-specific sidebar collapse */
      body.TEWGTB-BANGLA .admin-sidebar.collapsed ~ .admin-content{margin-left:70px!important}
      body.TEWGTB-ARABIC .admin-sidebar.collapsed ~ .admin-content{margin-left:0!important;margin-right:70px!important}
      body.TEWGTB-URDU .admin-sidebar.collapsed ~ .admin-content{margin-left:0!important;margin-right:70px!important}
      body.TEWGTB-HINDI .admin-sidebar.collapsed ~ .admin-content{margin-left:70px!important}
      .admin-sidebar{display:flex;flex-direction:column;min-height:100vh}
      .admin-sidebar .nav::-webkit-scrollbar{display:none}
      .admin-sidebar .nav{-ms-overflow-style:none;scrollbar-width:none}
      .admin-sidebar .sidebar-brand{height:auto;min-height:64px}
      .admin-sidebar.collapsed .sidebar-brand{height:auto;min-height:64px}
    </style>
    @stack('styles')
</head>
<body class="admin-body"
      data-flash-success="{{ session('success') }}"
      data-flash-error="{{ session('error') }}">

<div class="admin-wrapper">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-plane site-logo-icon"></i>
                <span>{{ config('app.name') }}</span>
            </div>
            <button type="button" class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
                <i class="fa-solid fa-chevron-left collapse-icon" id="sidebarCollapseIcon"></i>
                <i class="fa-solid fa-chevron-right btn-icon" id="sidebarExpandIcon" style="display:none"></i>
            </button>
        </div>

        <nav class="nav flex-column pb-4">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
            </a>

            <div class="nav-section-title"><span>CRM</span></div>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i><span>Customers</span>
            </a>
            <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <i class="fa-solid fa-address-book"></i><span>Leads</span>
            </a>

            <div class="nav-section-title"><span>Messages</span></div>
            <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope"></i><span>Messages</span>
            </a>
            <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comment-alt"></i><span>Comments</span>
            </a>

            <div class="nav-section-title"><span>Bookings</span></div>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i><span>Bookings</span>
            </a>
            <a href="{{ route('admin.visas.index') }}" class="nav-link {{ request()->routeIs('admin.visas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-passport"></i><span>Visa Applications</span>
            </a>
            <a href="{{ route('admin.flights.index') }}" class="nav-link {{ request()->routeIs('admin.flights.*') ? 'active' : '' }}">
                <i class="fa-solid fa-plane"></i><span>Flight Requests</span>
            </a>
            <a href="{{ route('admin.umrah.index') }}" class="nav-link {{ request()->routeIs('admin.umrah.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building"></i><span>Umrah Packages</span>
            </a>
            <a href="{{ route('admin.cargo.index') }}" class="nav-link {{ request()->routeIs('admin.cargo.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i><span>Cargo</span>
            </a>

            <div class="nav-section-title"><span>Finance</span></div>
            <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i><span>Invoices</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-credit-card"></i><span>Payments</span>
            </a>

            <div class="nav-section-title"><span>HR</span></div>
            <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <i class="fa-solid fa-id-badge"></i><span>Employees</span>
            </a>
            <a href="{{ route('admin.leave-requests.index') }}" class="nav-link {{ request()->routeIs('admin.leave-requests.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i><span>Leave Requests</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock"></i><span>Attendance</span>
            </a>
            <a href="{{ route('admin.payroll.index') }}" class="nav-link {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i><span>Payroll</span>
            </a>
            <a href="{{ route('admin.biometric-devices.index') }}" class="nav-link {{ request()->routeIs('admin.biometric-devices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-fingerprint"></i><span>Biometric Devices</span>
            </a>

            <div class="nav-section-title"><span>Accounting</span></div>
            <a href="{{ route('admin.chart-of-accounts.index') }}" class="nav-link {{ request()->routeIs('admin.chart-of-accounts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-university"></i><span>Chart of Accounts</span>
            </a>
            <a href="{{ route('admin.ledger-entries.index') }}" class="nav-link {{ request()->routeIs('admin.ledger-entries.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book"></i><span>Ledger Entries</span>
            </a>
            <a href="{{ route('admin.expense-claims.index') }}" class="nav-link {{ request()->routeIs('admin.expense-claims.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i><span>Expense Claims</span>
            </a>

            <div class="nav-section-title"><span>Recruitment</span></div>
            <a href="{{ route('admin.job-postings.index') }}" class="nav-link {{ request()->routeIs('admin.job-postings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase"></i><span>Job Postings</span>
            </a>
            <a href="{{ route('admin.job-applications.index') }}" class="nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-plus"></i><span>Job Applications</span>
            </a>

            <div class="nav-section-title"><span>CMS</span></div>
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-alt"></i><span>Pages</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list"></i><span>Menus</span>
            </a>
            <a href="{{ route('admin.blog-posts.index') }}" class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i><span>Blog Posts</span>
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comment"></i><span>Testimonials</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="fa-solid fa-images"></i><span>Gallery</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-question-circle"></i><span>FAQs</span>
            </a>

            <div class="nav-section-title"><span>Support</span></div>
            <a href="{{ route('admin.newsletter-subscribers.index') }}" class="nav-link {{ request()->routeIs('admin.newsletter-subscribers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-mailbox"></i><span>Newsletter</span>
            </a>

            <div class="nav-section-title"><span>Settings</span></div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-cog"></i><span>Settings</span>
            </a>
            <a href="{{ route('admin.settings.tax') }}" class="nav-link {{ request()->routeIs('admin.settings.tax*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i><span>Tax & VAT</span>
            </a>
            <a href="{{ route('admin.settings.backup') }}" class="nav-link {{ request()->routeIs('admin.settings.backup*') ? 'active' : '' }}">
                <i class="fa-solid fa-database"></i><span>Backup</span>
            </a>
            <a href="{{ route('admin.settings.redirects') }}" class="nav-link {{ request()->routeIs('admin.settings.redirects*') ? 'active' : '' }}">
                <i class="fa-solid fa-link"></i><span>Redirects</span>
            </a>
            <a href="{{ route('admin.settings.maintenance') }}" class="nav-link {{ request()->routeIs('admin.settings.maintenance*') ? 'active' : '' }}">
                <i class="fa-solid fa-wrench"></i><span>Maintenance</span>
            </a>
            <a href="{{ route('admin.seo-settings.index') }}" class="nav-link {{ request()->routeIs('admin.seo-settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-search"></i><span>SEO Settings</span>
            </a>
            <a href="{{ route('admin.social-links.index') }}" class="nav-link {{ request()->routeIs('admin.social-links.*') ? 'active' : '' }}">
                <i class="fa-solid fa-share-alt"></i><span>Social Links</span>
            </a>
            <a href="{{ route('admin.notices.index') }}" class="nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i><span>Notices</span>
            </a>
            <a href="{{ route('admin.translations.index') }}" class="nav-link {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-language"></i><span>Translations</span>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-shield-alt"></i><span>Audit Logs</span>
            </a>

            <div class="nav-section-title"><span>User Management</span></div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i><span>Users</span>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i><span>Roles & Permissions</span>
            </a>
            <a href="{{ route('admin.integrations.index') }}" class="nav-link {{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-plug"></i><span>Integrations</span>
            </a>

            <div class="nav-section-title"><span>AI & WhatsApp</span></div>
            <a href="{{ route('admin.chat-bot.index') }}" class="nav-link {{ request()->routeIs('admin.chat-bot.*') ? 'active' : '' }}">
                <i class="fa-solid fa-robot"></i><span>AI Chat Assistant</span>
            </a>
            <a href="{{ route('admin.whatsapp-broadcast.index') }}" class="nav-link {{ request()->routeIs('admin.whatsapp-broadcast.*') ? 'active' : '' }}">
                <i class="fa-brands fa-whatsapp"></i><span>WhatsApp Broadcast</span>
            </a>

            <div class="nav-section-title"><span>City TV Connect</span></div>
            <a href="{{ route('admin.city-tv-connect.index') }}" class="nav-link {{ request()->routeIs('admin.city-tv-connect.*') ? 'active' : '' }}">
                <i class="fa-solid fa-satellite-dish"></i><span>Branch Management</span>
            </a>
            <a href="{{ route('admin.city-tv-connect.cameras') }}" class="nav-link" target="_blank">
                <i class="fa-solid fa-video"></i><span>Live Cameras</span>
            </a>

            <div class="nav-section-title"><span>Account</span></div>
            <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="fa-solid fa-id-badge"></i><span>My Profile</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="admin-content">
        <header class="admin-topbar">
            <button class="sidebar-toggle-btn" aria-label="Toggle sidebar" onclick="toggleMobileSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="d-flex align-items-center gap-3">
                {{-- Theme Toggle --}}
                <button class="theme-toggle-btn" onclick="pcToggleTheme()" title="Toggle Theme">
                    <i class="fa-solid fa-sun" id="pcSun"></i>
                    <i class="fa-solid fa-moon" id="pcMoon" style="display:none"></i>
                </button>

                {{-- Language Switcher --}}
                <div class="gtranslate-wrap">
                    <button class="gt-btn" onclick="document.body.classList.toggle('gt-open')">
                        <i class="fa-solid fa-globe"></i>
                        <span class="d-none d-md-inline">Language</span>
                    </button>
                    <div class="lang-menu" id="google_translate_element"></div>
                </div>

                {{-- User Dropdown --}}
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4F2FE8&color=fff' }}" class="rounded-circle object-fit-cover" alt="Avatar" style="width:36px;height:36px">
                        <span class="d-none d-md-inline small fw-semibold">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa-solid fa-id-badge me-2"></i>My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="admin-main">
            @yield('content')
        </main>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="{{ asset('assets/js/admin.js') }}"></script>
<script>
    // Laravel File Manager popup helper
    function openFileManager(inputId, previewId) {
        window.addEventListener('message', function handler(e) {
            if (typeof e.data === 'string' && e.data.startsWith('http')) {
                var input = document.getElementById(inputId);
                if (input) input.value = e.data;
                var preview = previewId ? document.getElementById(previewId) : null;
                if (preview) preview.src = e.data;
                window.removeEventListener('message', handler);
            }
        });
        window.open('{{ url('laravel-filemanager') }}?type=Images', 'FileManager', 'width=900,height=600');
    }
</script>

@stack('scripts')

{{-- Light / dark toggle --}}
<script>
  function pcToggleTheme(){
    var el=document.documentElement, dark=el.getAttribute('data-theme')==='dark';
    if(dark){el.removeAttribute('data-theme');try{localStorage.setItem('pc-theme','light')}catch(e){}}
    else{el.setAttribute('data-theme','dark');try{localStorage.setItem('pc-theme','dark')}catch(e){}}
    pcSyncThemeIcon();
  }
  function pcSyncThemeIcon(){
    var dark=document.documentElement.getAttribute('data-theme')==='dark';
    var s=document.getElementById('pcSun'), m=document.getElementById('pcMoon');
    if(s&&m){s.style.display=dark?'none':'inline';m.style.display=dark?'inline':'none';}
  }
  pcSyncThemeIcon();
  
  // Mobile sidebar toggle
  function toggleMobileSidebar(){
    var sidebar = document.querySelector('.admin-sidebar');
    if(sidebar){
      sidebar.classList.toggle('show');
    }
  }
</script>

{{-- Google Translate --}}
<script type="text/javascript">
  function googleTranslateElementInit(){
    new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'en,ar,bn,ur,hi,tl,af,sq,am,hy,az,eu,be,bs,ca,ceb,ny,zh-CN,zh-TW,co,hr,cs,da,nl,eo,et,fil,fi,fr,fy,gl,ka,de,el,gu,ht,ha,haw,iw,hmn,hu,is,ig,id,ga,it,ja,jw,kn,kk,km,rw,ko,ku,ky,lo,la,lv,lt,lb,mk,mg,ms,ml,mt,mi,mr,mn,my,ne,no,ps,fa,pl,pt,pa,ro,ru,sm,gd,sr,st,sn,sd,si,sk,sl,so,es,su,sw,sv,tg,ta,tt,te,th,tr,tk,uk,ug,uz,vi,cy,xh,yi,yo,zu',layout:google.translate.TranslateElement.InlineLayout.SIMPLE,autoDisplay:false},'google_translate_element');
  }
  function pickLang(lang){
    document.body.classList.remove('gt-open');
    var host = location.hostname;
    var val = '/en/' + lang;
    document.cookie = 'googtrans=' + val + ';path=/';
    document.cookie = 'googtrans=' + val + ';path=/;domain=' + host;
    document.cookie = 'googtrans=' + val + ';path=/;domain=.' + host;
    if(lang === 'en'){
      document.cookie = 'googtrans=;path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT';
      document.cookie = 'googtrans=;path=/;domain=' + host + ';expires=Thu, 01 Jan 1970 00:00:00 GMT';
      document.cookie = 'googtrans=;path=/;domain=.' + host + ';expires=Thu, 01 Jan 1970 00:00:00 GMT';
    }
    location.reload();
  }
  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    var gtranslateWrap = document.querySelector('.gtranslate-wrap');
    if(gtranslateWrap && !gtranslateWrap.contains(e.target)) {
      document.body.classList.remove('gt-open');
    }
  });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
// Apply language-specific fonts
(function() {
  function applyFonts() {
    var m = document.cookie.match(/googtrans=\/[^\/]+\/([a-z-]+)/);
    var lang = m ? m[1] : '';
    
    // Bengali
    if (lang === 'bn' || lang === 'bengali') {
      document.body.classList.add('TEWGTB-BANGLA');
      document.body.classList.remove('TEWGTB-ARABIC');
    }
    // Arabic and other RTL languages
    else if (lang === 'ar' || lang === 'ur' || lang === 'fa' || lang === 'he' || lang === 'ps' || lang === 'sd') {
      document.body.classList.add('TEWGTB-ARABIC');
      document.body.classList.remove('TEWGTB-BANGLA');
    }
    // English / others
    else {
      document.body.classList.remove('TEWGTB-BANGLA');
      document.body.classList.remove('TEWGTB-ARABIC');
    }
  }
  
  // Apply on page load
  applyFonts();
  
  // Watch for language changes
  setInterval(applyFonts, 1000);
  
  // Also apply when translation happens
  var observer = new MutationObserver(applyFonts);
  observer.observe(document.body, { childList: true, subtree: true });
})();

// Sidebar collapse functionality
(function(){
  var sidebar = document.querySelector('.admin-sidebar');
  var collapseIcon = document.getElementById('sidebarCollapseIcon');
  var expandIcon = document.getElementById('sidebarExpandIcon');
  if(localStorage.getItem('sidebar-collapsed') === 'true' && sidebar) {
    sidebar.classList.add('collapsed');
    if(collapseIcon) collapseIcon.style.display = 'none';
    if(expandIcon) expandIcon.style.display = 'inline';
  }
})();

function toggleSidebarCollapse() {
  var sidebar = document.querySelector('.admin-sidebar');
  var collapseIcon = document.getElementById('sidebarCollapseIcon');
  var expandIcon = document.getElementById('sidebarExpandIcon');
  if(sidebar) {
    sidebar.classList.toggle('collapsed');
    if(sidebar.classList.contains('collapsed')) {
      if(collapseIcon) collapseIcon.style.display = 'none';
      if(expandIcon) expandIcon.style.display = 'inline';
      localStorage.setItem('sidebar-collapsed', 'true');
    } else {
      if(collapseIcon) collapseIcon.style.display = 'inline';
      if(expandIcon) expandIcon.style.display = 'none';
      localStorage.setItem('sidebar-collapsed', 'false');
    }
  }
}

// Function to update collapse icons based on RTL
function updateCollapseIconsRTL() {
  var m = document.cookie.match(/googtrans=\/[^\/]+\/([a-z-]+)/);
  var lang = m ? m[1] : '';
  var rtl = ['ar', 'ur', 'fa', 'he', 'ps', 'sd'];
  var isRtl = rtl.indexOf(lang) >= 0 || document.documentElement.getAttribute('dir') === 'rtl';
  
  var collapseIcon = document.getElementById('sidebarCollapseIcon');
  var expandIcon = document.getElementById('sidebarExpandIcon');
  
  if (collapseIcon) {
    collapseIcon.style.transform = isRtl ? 'scaleX(-1)' : 'scaleX(1)';
  }
  if (expandIcon) {
    expandIcon.style.transform = isRtl ? 'scaleX(-1)' : 'scaleX(1)';
  }
}

// Run on page load
document.addEventListener('DOMContentLoaded', updateCollapseIconsRTL);

// Run when translation changes
setInterval(updateCollapseIconsRTL, 1000);
</script>
</body>
</html>
