<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ daisyui_default_preset() }}"
    data-default-theme="{{ daisyui_default_preset() }}">

<head>
    @tcmsDaisyUIBoot
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Meta Tags --}}
    <x-tcms::seo.meta-tags :title="$title ?? null" :description="$description ?? null" :image="isset($featuredImage) && $featuredImage ? Storage::disk(cms_media_disk())->url($featuredImage) : null" :type="$seoType ?? 'website'" :article="$seoArticle ?? null"
        :twitter="$seoTwitter ?? null" :profile="$seoProfile ?? null" />

    {{-- Structured Data --}}
    <x-tcms::seo.structured-data :page="$seoPage ?? null" :post="$seoPost ?? null" :breadcrumbs="$seoBreadcrumbs ?? null" :includeWebsite="$seoIncludeWebsite ?? false" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    @if ($favicon = tcms_favicon())
        <link rel="icon" href="{{ Storage::disk(cms_media_disk())->url($favicon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- CMS Core Runtime (shared Alpine components) -->
    @tcmsCoreJs
    <!-- Theme Assets -->
    @themeVite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navigation progress bar */
        #nav-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: oklch(var(--p));
            z-index: 9999;
            transform: scaleX(0);
            transform-origin: left;
            pointer-events: none;
        }

        #nav-progress.loading {
            animation: nav-progress 2s ease-out forwards;
        }

        #nav-progress.done {
            animation: none;
            transform: scaleX(1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        @keyframes nav-progress {
            0% {
                transform: scaleX(0);
            }

            20% {
                transform: scaleX(0.5);
            }

            80% {
                transform: scaleX(0.8);
            }

            100% {
                transform: scaleX(0.95);
            }
        }

        /* Content fade during navigation */
        .page-transitioning {
            opacity: 0.5;
            transition: opacity 0.1s ease;
        }
    </style>
    <x-tcms::code-injection zone="head" />
</head>

<body class="min-h-screen bg-base-100 text-base-content">
    <x-tcms::code-injection zone="body_start" />
    <!-- Navigation Progress Bar -->
    <div class="navigation-progress" id="nav-progress"></div>
    @if (supports_theme_controller())
        <!-- Theme Drawer Wrapper -->
        <div class="drawer drawer-end">
            <input id="theme-drawer" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content">
    @endif
    <!-- Navbar -->
    @if (function_exists('mega_menu_header_active') && mega_menu_header_active('header'))
        {{-- Mega Menu Full Header --}}
        <x-dynamic-component component="mega-menu::header" location="header" />
    @else
        {{-- Original theme navbar --}}
        <div class="navbar bg-base-100 shadow-sm sticky top-0 z-50">
            <div class="navbar-start">
                <!-- Mobile Menu -->
                <div class="dropdown lg:hidden">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-lg bg-base-100 rounded-box w-52">
                        <x-menu location="header" style="vertical" />
                    </ul>
                </div>
                <!-- Logo -->
                @php
                    $logo = tcms_site_setting('logo');
                    $siteName = tcms_site_setting_string('site_name', (string) config('app.name'));
                @endphp
                <a href="{{ tcms_home_url() }}" class="btn btn-ghost text-xl font-bold">
                    @if ($logo)
                        <img src="{{ Storage::disk(cms_media_disk())->url($logo) }}" alt="{{ $siteName }}"
                            class="h-8 w-auto">
                    @else
                        {{ $siteName }}
                    @endif
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <x-menu location="header" style="horizontal" />
                </ul>
            </div>

            <div class="navbar-end gap-2">
                <!-- Search -->
                @include('theme.talldaisy::components.header-search')

                <!-- Language Switcher -->
                @include('theme.talldaisy::components.language-switcher')

                <!-- Theme Switcher -->
                @if (supports_theme_controller())
                    @include('theme.talldaisy::components.theme-switcher')
                @endif
            </div>
        </div>
    @endif

    {{-- Breadcrumbs --}}
    @if ($showBreadcrumbs ?? false)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-tcms::breadcrumbs :items="$breadcrumbItems ?? []" :over-hero="$breadcrumbsOverHero ?? false" />
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer footer-center bg-base-200 text-base-content p-10">
        <aside>
            @php
                $logo = tcms_site_setting('logo');
                $siteName = tcms_site_setting_string('site_name', (string) config('app.name'));
            @endphp
            @if ($logo)
                <img src="{{ Storage::disk(cms_media_disk())->url($logo) }}" alt="{{ $siteName }}"
                    class="h-10 w-auto mb-2">
            @else
                <p class="font-bold text-lg">{{ $siteName }}</p>
            @endif
            <p>{{ tcms_site_setting_string('site_tagline', 'A modern content management system built on the TALL stack.') }}
            </p>
        </aside>

        <nav>
            <x-menu location="footer" style="footer" />
        </nav>

        <aside>
            <p>&copy; {{ date('Y') }} {{ tcms_site_setting_string('site_name', (string) config('app.name')) }}.
                All rights reserved.</p>
            <x-tcms::powered-by />
        </aside>
    </footer>
    @if (supports_theme_controller())
        </div>

        <!-- Theme Drawer Sidebar -->
        <div class="drawer-side z-[60]">
            <label for="theme-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="bg-base-200 min-h-full w-80 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Choose Theme</h2>
                    <label for="theme-drawer" class="btn btn-sm btn-circle btn-ghost">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>
                <ul class="menu w-full p-0 gap-1" id="theme-list">
                    @foreach (daisyui_presets() as $preset)
                        <li>
                            <button type="button" class="btn btn-sm btn-block btn-ghost justify-start theme-btn"
                                data-theme-value="{{ $preset }}">
                                <span class="badge badge-sm" data-theme="{{ $preset }}">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                    <span class="w-2 h-2 rounded-full bg-accent"></span>
                                </span>
                                {{ ucfirst($preset) }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        </div>
    @endif

    <script>
        // Theme switcher - explicit control without relying on theme-controller class
        (function() {
            function setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('tcms-theme', theme);
                // Update active button styling
                document.querySelectorAll('.theme-btn').forEach(btn => {
                    btn.classList.toggle('btn-active', btn.dataset.themeValue === theme);
                });
                // Close the drawer after selection
                const drawer = document.getElementById('theme-drawer');
                if (drawer) drawer.checked = false;
            }

            function initThemeButtons() {
                const savedTheme = document.documentElement.getAttribute('data-theme');
                document.querySelectorAll('.theme-btn').forEach(btn => {
                    btn.classList.toggle('btn-active', btn.dataset.themeValue === savedTheme);
                    // Remove existing listener to avoid duplicates after navigation
                    btn.removeEventListener('click', btn._themeClickHandler);
                    btn._themeClickHandler = function() {
                        setTheme(this.dataset.themeValue);
                    };
                    btn.addEventListener('click', btn._themeClickHandler);
                });
            }

            // Initialize on page load
            initThemeButtons();

            // Navigation transitions
            document.addEventListener('livewire:navigate', function() {
                const bar = document.getElementById('nav-progress');
                const main = document.querySelector('main');
                if (bar) {
                    bar.classList.remove('done');
                    bar.classList.add('loading');
                }
                if (main) {
                    main.classList.add('page-transitioning');
                }
            });

            document.addEventListener('livewire:navigated', function() {
                const bar = document.getElementById('nav-progress');
                const main = document.querySelector('main');
                if (bar) {
                    bar.classList.remove('loading');
                    bar.classList.add('done');
                    setTimeout(() => bar.classList.remove('done'), 400);
                }
                if (main) {
                    main.classList.remove('page-transitioning');
                }
                // Re-apply theme: localStorage override or current data-theme (already set by boot script)
                var savedTheme = localStorage.getItem('tcms-theme');
                if (savedTheme) {
                    document.documentElement.setAttribute('data-theme', savedTheme);
                }
                initThemeButtons();
            });
        })();
    </script>

    {{-- Register T-CMS Alpine plugins before Livewire starts Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {
            if (window.__tcmsPlugins?.length) {
                window.__tcmsPlugins.forEach(plugin => window.Alpine.plugin(plugin));
                window.__tcmsPlugins = []; // Clear to prevent double registration
            }
        });
    </script>
    @livewireScripts
    <x-tcms::code-injection zone="body_end" />
</body>

</html>
