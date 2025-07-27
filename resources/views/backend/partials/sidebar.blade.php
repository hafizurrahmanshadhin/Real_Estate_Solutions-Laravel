@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<style>
    .navbar-menu .navbar-nav .nav-sm .nav-link:before {
        display: none;
    }
</style>

<div class="app-menu navbar-menu">
    {{-- Logo & Toggle Button --}}
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/PNG FILE-01-02.png') }}" alt="Logo"
                    height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/PNG FILE-01-02.png') }}" alt="Logo"
                    height="125" style="margin-left: -50px;">
            </span>
        </a>

        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>

        <div class="vertical-menu-btn-wrapper header-item vertical-icon">
            <button type="button"
                class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow hamburger-icon"
                id="topnav-hamburger-icon">
                <i class='bx bx-chevrons-right'></i>
                <i class='bx bx-chevrons-left'></i>
            </button>
        </div>
    </div>
    {{-- Logo & Toggle Button --}}

    <div id="scrollbar">
        {{-- Sidebar Start --}}
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- Services Area --}}
                <li class="nav-item">
                    <a href="{{ route('service-area.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('service-area.*') ? 'active' : '' }}">
                        <i class="ri-map-pin-line"></i>
                        <span data-key="t-service-area">Services Area</span>
                    </a>
                </li>

                {{-- Package --}}
                <li class="nav-item">
                    <a href="{{ route('package.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('package.*') ? 'active' : '' }}">
                        <i class="ri-archive-line"></i>
                        <span data-key="t-package">Packages</span>
                    </a>
                </li>

                {{-- Services --}}
                <li class="nav-item">
                    <a href="{{ route('service.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('service.*') ? 'active' : '' }}">
                        <i class="ri-gallery-line"></i>
                        <span data-key="t-service">Services</span>
                    </a>
                </li>

                {{-- Other Services --}}
                <li class="nav-item">
                    <a href="{{ route('other-service.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('other-service.*') ? 'active' : '' }}">
                        <i class="ri-stack-line"></i>
                        <span data-key="t-other-service">Other Services</span>
                    </a>
                </li>

                {{-- Order Request --}}
                <li class="nav-item">
                    <a href="{{ route('order-request.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('order-request.*') ? 'active' : '' }}">
                        <i class="ri-shopping-cart-line"></i>
                        <span data-key="t-order-request">Other Services<br>Order Request</span>
                    </a>
                </li>

                {{-- Contact Message --}}
                <li class="nav-item">
                    <a href="{{ route('contact-message.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('contact-message.*') ? 'active' : '' }}">
                        <i class="ri-mail-line"></i>
                        <span data-key="t-contact-message">Contact Message</span>
                    </a>
                </li>

                <hr>
                {{-- CMS --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('admin/cms/*') ? 'active' : '' }}" href="#sidebarCms"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/cms/*') ? 'true' : 'false' }}"
                        aria-controls="sidebarCms">
                        <i class="ri-file-list-line"></i>
                        <span data-key="t-cms">CMS</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->is('admin/cms/*') ? 'show' : '' }}"
                        id="sidebarCms">
                        <ul class="nav nav-sm flex-column">
                            {{-- Home Page --}}
                            <li class="nav-item">
                                <a href="{{ route('home-page.hero-section.index') }}"
                                    class="nav-link {{ request()->routeIs('home-page.hero-section.*') ? 'active' : '' }}"
                                    data-key="t-home-page.hero-section" style="white-space: nowrap">
                                    <i class="ri-checkbox-blank-circle-fill"
                                        style="font-size:0.6rem; margin-right:-1rem;"></i>
                                    Home Page
                                </a>
                            </li>

                            {{-- Contact Page --}}
                            <li class="nav-item">
                                <a href="{{ route('contact-page.hero-section.index') }}"
                                    class="nav-link {{ request()->routeIs('contact-page.hero-section.*') ? 'active' : '' }}"
                                    data-key="t-contact-page.hero-section" style="white-space: nowrap">
                                    <i class="ri-checkbox-blank-circle-fill"
                                        style="font-size:0.6rem; margin-right:-1rem;"></i>
                                    Contact Page
                                </a>
                            </li>

                            {{-- Other Page --}}
                            <li class="nav-item">
                                <a href="{{ route('other-page.hero-section.index') }}"
                                    class="nav-link {{ request()->routeIs('other-page.hero-section.*') ? 'active' : '' }}"
                                    data-key="t-other-page.hero-section" style="white-space: nowrap">
                                    <i class="ri-checkbox-blank-circle-fill"
                                        style="font-size:0.6rem; margin-right:-1rem;"></i>
                                    Other Page
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <hr>
                {{-- Settings --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                        href="#sidebarPages" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/settings*') ? 'true' : 'false' }}"
                        aria-controls="sidebarPages">
                        <i class="ri-settings-3-line"></i>
                        <span data-key="t-pages">Settings</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->is('admin/settings*') ? 'show' : '' }}"
                        id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('profile.setting') }}"
                                    class="nav-link {{ request()->routeIs('profile.setting') ? 'active' : '' }}"
                                    data-key="t-profile-setting">
                                    Profile Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('system.index') }}"
                                    class="nav-link {{ request()->routeIs('system.index') ? 'active' : '' }}"
                                    data-key="t-system-settings">
                                    System Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('mail.setting') }}"
                                    class="nav-link {{ request()->routeIs('mail.setting') ? 'active' : '' }}"
                                    data-key="t-mail.setting">
                                    SMTP Server
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('integration.setting') }}"
                                    class="nav-link {{ request()->routeIs('integration.setting') ? 'active' : '' }}"
                                    data-key="t-integration-settings">
                                    Integration Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('social.index') }}"
                                    class="nav-link {{ request()->routeIs('social.index') ? 'active' : '' }}"
                                    data-key="t-social-media-settings">
                                    Social Media Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('terms-and-conditions.index') }}"
                                    class="nav-link {{ request()->routeIs('terms-and-conditions.index') ? 'active' : '' }}"
                                    data-key="t-terms-and-conditions">
                                    Terms & Conditions
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('privacy-policy.index') }}"
                                    class="nav-link {{ request()->routeIs('privacy-policy.index') ? 'active' : '' }}"
                                    data-key="t-privacy-policy">
                                    Privacy Policy
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        {{-- Sidebar End --}}
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
