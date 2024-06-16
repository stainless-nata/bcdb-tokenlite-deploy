<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ site_whitelabel('apps') }}">
    <meta name="author" content="{{ site_whitelabel('author') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <link rel="shortcut icon" href="{{ site_favicon() }}">
    <title>@yield('title') | {{ site_whitelabel('title') }}</title>
    <link rel="stylesheet" href="{{ asset(style_theme('vendor')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('admin')) }}">
    @stack('header')
</head>

<body class="admin-dashboard page-user">
    <div class="topbar-wrap">
        <div class="topbar is-sticky">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="topbar-nav d-lg-none">
                        <li class="topbar-nav-item relative">
                            <a class="toggle-nav" href="#">
                                <div class="toggle-icon">
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                </div>
                            </a>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}
                    <div class="topbar-logo">
                        <a href="{{ url('/')}}" class="site-brand">
                            @if(site_whitelabel('admin'))
                                <img height="40" src="{{ site_whitelabel('logo-light') }}" srcset="{{ site_whitelabel('logo-light2x') }}" alt="{{ site_whitelabel('name') }}">
                            @else
                            <svg version="1.1" id="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 590 160" xml:space="preserve" height="40"><path d="m134.5 36.8-57.5-33.3c-3.5-2-7.8-2-11.3 0l-57.4 33.3c-3.5 2-5.6 5.8-5.6 9.8v66.7c0 4.1 2.2 7.8 5.6 9.8l57.4 33.3c1.7 1 3.7 1.5 5.6 1.5s3.9-0.5 5.6-1.5l57.4-33.3c3.5-2 5.6-5.8 5.6-9.8v-66.6c0.2-4.1-1.9-7.8-5.4-9.9zm-4.1 9.8v54.5h-24.5l10.8-63.6 12.7 7.4c0.6 0.4 1 1 1 1.7zm-35.9 62.3h35.8v4.4c0 0.2 0 0.3-0.1 0.5h-41l0.8-4.4 13.3-79.6 4 2.3-12.8 76.8zm-83.1 4.5v-56.4h32.9l-12.3 69.5-19.7-11.4c-0.5-0.4-0.9-1-0.9-1.7zm60.5 35.1c-0.6 0.3-1.3 0.3-1.9 0l-16.4-9.5 14.4-82h21.7l1.4-7.8h-32.1l-14.7 84.4-4.1-2.4 14.3-82.1h-43.1v-2.6c0-0.7 0.4-1.3 1-1.7l1.1-0.7h78.3l1.3-7.8h-66.1l42.9-24.9c0.3-0.2 0.6-0.3 1-0.3 0.3 0 0.7 0.1 1 0.3l23.3 13.5-16.4 96.6h39.2l-46.1 27z" fill="{{ style_theme('admin-color', 0) }}"/><path d="m167.4 62.9v-10h44.5v10h-16.3v44.3h-11.9v-44.3h-16.3zm52.1 6.1c1.2-3.5 3-6.5 5.2-9.1 2.3-2.6 5.1-4.6 8.4-6.1s7.1-2.2 11.2-2.2c4.2 0 8 0.7 11.3 2.2s6.1 3.5 8.4 6.1 4 5.6 5.2 9.1 1.8 7.2 1.8 11.3c0 4-0.6 7.6-1.8 11.1-1.2 3.4-3 6.4-5.2 8.9-2.3 2.5-5.1 4.5-8.4 6-3.3 1.4-7 2.2-11.3 2.2-4.2 0-7.9-0.7-11.2-2.2-3.3-1.4-6.1-3.4-8.4-6-2.3-2.5-4-5.5-5.2-8.9s-1.8-7.1-1.8-11.1c0-4.1 0.6-7.9 1.8-11.3zm10.9 17.9c0.5 2.2 1.4 4.1 2.5 5.8 1.2 1.7 2.7 3.1 4.6 4.1s4.2 1.6 6.8 1.6c2.7 0 5-0.5 6.8-1.6 1.9-1 3.4-2.4 4.6-4.1s2-3.7 2.5-5.8c0.5-2.2 0.8-4.4 0.8-6.7 0-2.4-0.3-4.7-0.8-6.9s-1.4-4.2-2.5-6c-1.2-1.7-2.7-3.1-4.6-4.2-1.9-1-4.2-1.6-6.8-1.6-2.7 0-5 0.5-6.8 1.6-1.9 1-3.4 2.4-4.6 4.2-1.2 1.7-2 3.7-2.5 6-0.5 2.2-0.8 4.5-0.8 6.9 0 2.3 0.3 4.6 0.8 6.7zm62.7-34v22.5l21.2-22.5h14.9l-21.2 21.4 23.3 32.9h-15l-16.4-24.4-6.8 6.9v17.5h-11.9v-54.3h11.9zm47 21.7h29.3v9.3h-29.3v-9.3zm0-21.7h29.3v10h-29.3v-10zm0 44.2h29.3v10h-29.3v-10zm51.1-44.2 22.7 36.4h0.2v-36.4h11.2v54.3h-11.9l-22.6-36.4h-0.2v36.4h-11.2v-54.3h11.8z" fill="#fff"/><path d="m445.5 52.9v48.2h28.7v6.1h-36v-54.3h7.3zm44.5 0v54.3h-7.2v-54.3h7.2zm8.3 6.1v-6.1h43.4v6.1h-18.1v48.2h-7.2v-48.2h-18.1zm88.8-6.1v6.1h-30.3v17.3h28.2v6.1h-28.2v18.8h30.5v6.1h-37.7v-54.4h37.5z" fill="#E1E1EB"/></svg>
                            @endif
                        </a>
                    </div>
                    <ul class="topbar-nav">
                        <li class="topbar-nav-item relative">
                            <span class="user-welcome d-none d-lg-inline-block">Hello! {{ ucfirst(auth()->user()->role) }}</span>
                            <a class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
                                <div class="user-status">
                                    <h6 class="user-status-title">{{ auth()->user()->name }} <span class="text-white-50">({{ set_id(auth()->user()->id) }})</span></h6>
                                    <div class="user-status-balance"><small>{{ auth()->user()->email }}</small></div>
                                </div>
                                <ul class="user-links">
                                    <li><a href="{{ route('admin.profile') }}"><i class="ti ti-id-badge"></i>My Profile</a></li>

                                    <li><a href="{{ route('admin.profile.activity') }}"><i class="ti ti-eye"></i>Activity</a></li>
                                </ul>
                                <ul class="user-links bg-light">
                                    <li><a href="{{ route('log-out') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ti ti-power-off"></i>Logout</a></li>
                                </ul>
                            </div>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}
                </div>
            </div>{{-- .container --}}
        </div>{{-- .topbar --}}
        <div class="navbar">
            <div class="container">
                <div class="navbar-innr">
                    <ul class="navbar-menu" id="main-nav">
                        <li><a href="{{ route('admin.home') }}"><em class="ikon ikon-dashboard"></em> Dashboard</a></li>
                        @if(gup('tranx')||gup('view_tranx'))
                        <li{!! ((is_page('transactions')||is_page('transactions.pending')||is_page('transactions.approved')||is_page('transactions.bonuses'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.transactions', 'pending') }}"><em class="ikon ikon-transactions"></em> Transactions</a>
                        </li>
                        @endif
                        @if(nio_module()->has('Withdraw') && has_route('withdraw:admin.index') && gup('withdraw'))
                        <li{!! ((is_page('withdraw'))? ' class="active"' : '') !!}>
                            <a href="{{ route('withdraw:admin.index') }}"><em class="ikon ikon-wallet"></em> Withdraw</a>
                        </li>
                        @endif
                        @if(gup('kyc')||gup('view_kyc'))
                        <li{!! ((is_page('kyc-list')||is_page('kyc-list.pending')||is_page('kyc-list.approved')||is_page('kyc-list.missing'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.kycs', 'pending') }}"><em class="ikon ikon-docs"></em> KYC List</a>
                        </li>
                        @endif
                        @if(gup('user')||gup('view_user'))
                        <li{!! ((is_page('users')||is_page('users.user')||is_page('users.admin'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.users', 'user') }}"><em class="ikon ikon-user-list"></em> Users List</a>
                        </li>
                        @endif
                        @if(gup('stage'))
                        <li{!! ((is_page('stages'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.stages') }}"><em class="ikon ikon-coins"></em> ICO/STO Stage</a>
                        </li>
                        @endif
                        @if(gup('setting'))
                        <li class="has-dropdown"><a class="drop-toggle" href="javascript:void(0)"><em class="ikon ikon-settings"></em> Settings</a>
                            <ul class="navbar-dropdown">
                                <li><a href="{{ route('admin.stages.settings') }}">ICO/STO Setting</a></li>
                                <li><a href="{{ route('admin.settings') }}">Website Setting</a></li>
                                <li><a href="{{ route('admin.settings.referral') }}">Referral Setting</a></li>
                                <li><a href="{{ route('admin.settings.email') }}">Mailing Setting</a></li>
                                <li><a href="{{ route('admin.payments.setup') }}">Payment Methods</a></li>
                                <li><a href="{{ route('admin.pages') }}">Manage Pages</a></li>
                                <li><a href="{{ route('admin.settings.api') }}">Application API</a></li>
                                <li><a href="{{ route('admin.lang.manage') }}">Manage Languages</a></li>
                                <li><a href="{{ route('admin.system') }}">System Status</a></li>
                                @if(has_route('manage_access:admin.index'))
                                <li><a href="{{ route('manage_access:admin.index') }}">Manage Admin</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                    @if(is_super_admin())
                    <ul class="navbar-btns">
                        <li><a id="clear-cache" class="btn btn-auto btn-xs btn-dark btn-outline" href="{{ route('admin.clear.cache') }}"><em class="ti ti-trash"></em><span>CLEAR CACHE</span></a></li>
                    </ul>
                    @endif
                </div>{{-- .navbar-innr --}}
            </div>{{-- .container --}}
        </div>{{-- .navbar --}}
    </div>{{-- .topbar-wrap --}}
    
    @yield('content')

    <div class="footer-bar">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-12">
                    <div class="copyright-text text-center pb-3">{!! site_whitelabel('copyright') !!}</div>
                </div>
            </div>
        </div>{{-- .container --}}
    </div>{{-- .footer-bar --}}
    <form id="logout-form" action="{{ (is_maintenance() ? route('admin.logout') : route('logout')) }}" method="POST" style="display: none;">
        @csrf
    </form>
    <div id="ajax-modal"></div>
    @yield('modals')
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>

@if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
@endif
@php
    $admin_routes = '';
    $route_urls = [
        'get_trnx_url' => 'admin.ajax.transactions.view',
        'view_user_url' => 'admin.ajax.users.view',
        'show_user_info' => 'admin.ajax.users.show',
        'pm_manage_url' => 'admin.ajax.payments.view',
        'get_kyc_url' => 'admin.ajax.kyc.ajax_show',
        'update_kyc_url' => 'admin.ajax.kyc.update',
        'trnx_action_url' => 'admin.ajax.transactions.update',
        'trnx_adjust_url' => 'admin.ajax.transactions.adjustement',
        'get_et_url' => 'admin.ajax.settings.email.template.view',
        'clear_cache_url' => 'admin.clear.cache',
        'whitepaper_uploads' => 'admin.ajax.pages.upload',
        'view_page_url' => 'admin.ajax.pages.view',
        'unverified_delete_url' => 'admin.ajax.users.delete',
        'stage_action_url' => 'admin.ajax.stages.actions',
        'stage_active_url' => 'admin.ajax.stages.active',
        'stage_pause_url' => 'admin.ajax.stages.pause',
        'quick_update_url' => 'admin.ajax.payments.qupdate',
        'transfer_action_url' => 'transfer:admin.update',
        'meta_update_url' => 'admin.ajax.settings.meta.update'
    ];
    foreach($route_urls as $var => $route) {
        $admin_routes .= (has_route($route)) ? $var.' = "'.route($route).'", ' : '';
    }
@endphp
    <script type="text/javascript">
        var base_url = "{{ url('/') }}", {!! $admin_routes !!} csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); 
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/admin.app.js').css_js_ver() }}"></script>
    @stack('footer')
    @if(session()->has('global'))
    <script type="text/javascript">
        show_toast("info","{{ session('global') }}");
    </script>
    @endif
</body>
</html>