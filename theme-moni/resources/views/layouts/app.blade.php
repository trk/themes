<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ daisyui_default_preset() }}" data-default-theme="{{ daisyui_default_preset() }}">
<head>
    <script>
        // Prevent theme flash before first render
        (function() {
            var savedTheme = localStorage.getItem('tcms-theme') || '{{ daisyui_default_preset() }}';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    @tcmsDaisyUIBoot
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Meta Tags --}}
    <x-tcms::seo.meta-tags
        :title="$title ?? null"
        :description="$description ?? null"
        :image="isset($featuredImage) && $featuredImage ? Storage::disk(cms_media_disk())->url($featuredImage) : null"
        :type="$seoType ?? 'website'"
        :article="$seoArticle ?? null"
        :twitter="$seoTwitter ?? null"
        :profile="$seoProfile ?? null"
    />

    {{-- Structured Data --}}
    <x-tcms::seo.structured-data
        :page="$seoPage ?? null"
        :post="$seoPost ?? null"
        :breadcrumbs="$seoBreadcrumbs ?? null"
        :includeWebsite="$seoIncludeWebsite ?? false"
    />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    @if($favicon = \Totoglu\Cms\Models\SiteSetting::get('favicon'))
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
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        /* Smooth luxury transition for theme toggling */
        body {
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <x-tcms::code-injection zone="head" />
</head>
<body class="min-h-screen bg-base-100 text-base-content font-sans antialiased">
    <x-tcms::code-injection zone="body_start" />
    
    <!-- Header -->
    @if(function_exists('mega_menu_header_active') && mega_menu_header_active('header'))
        {{-- Mega Menu Full Header --}}
        <x-dynamic-component component="mega-menu::header" location="header" />
    @elseif(function_exists('pro_header_active') && pro_header_active('header'))
        {{-- T-CMS Pro Full Header (Mode 2) - Legacy --}}
        <x-dynamic-component component="tcms-pro::full-header" location="header" />
    @else
        {{-- Theme's Default Luxury Navbar --}}
        <div class="navbar bg-base-100/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-base-300 transition-all duration-300">
            <div class="navbar-start">
                <!-- Mobile Menu -->
                <div class="dropdown lg:hidden">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle hover:bg-base-200/50">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                    <div tabindex="0" class="dropdown-content mt-3 z-[1] p-3 shadow-lg bg-base-100 rounded-box w-56 border border-base-300">
                        <x-menu location="header" style="vertical" />
                        
                        <!-- Mobile Language Switcher -->
                        <div class="divider my-2"></div>
                        <div class="flex items-center justify-center gap-4 py-1 text-sm font-semibold tracking-wider">
                            @php
                                $registry = app(\Totoglu\Cms\Services\LocaleRegistry::class);
                                $currentLocale = app()->getLocale();
                                $locales = $registry->getLocales();
                                $mobileModel = $page ?? $post ?? null;
                                $mobileUrls = [];
                                if ($mobileModel && method_exists($mobileModel, 'getTranslation')) {
                                    $mobileUrls = tcms_alternate_urls($mobileModel);
                                } else {
                                    foreach ($locales as $code => $locale) {
                                        $mobileUrls[$code] = tcms_localized_url(tcms_current_slug(), $code);
                                    }
                                }
                            @endphp
                            @foreach($locales as $code => $locale)
                                @php $url = $mobileUrls[$code] ?? tcms_localized_url(tcms_current_slug(), $code); @endphp
                                <a href="{{ url($url) }}" 
                                   class="px-2.5 py-1 rounded-md transition-all duration-200 {{ $code === $currentLocale ? 'text-primary bg-primary/10' : 'text-base-content/60 hover:text-base-content hover:bg-base-200' }}">
                                    {{ strtoupper($code) }}
                                </a>
                                @if(!$loop->last)
                                    <span class="text-base-content/20">|</span>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="divider my-2"></div>
                        <a href="https://www.reseliva.com/booknow/Moni-Hotel/?&lang={{ app()->getLocale() }}" target="_blank" class="btn btn-success btn-sm bg-[#22c55e] hover:bg-[#16a34a] border-[#22c55e] hover:border-[#16a34a] text-white font-semibold w-full text-center">
                            {{ app()->getLocale() === 'tr' ? 'Rezervasyon Yap' : 'Book Now' }}
                        </a>
                    </div>
                </div>
                <!-- Logo -->
                @php
                    $logo = \Totoglu\Cms\Models\SiteSetting::get('logo');
                    $siteName = \Totoglu\Cms\Models\SiteSetting::getCurrentSiteName(config('app.name'));
                @endphp
                <a href="{{ tcms_home_url() }}" class="btn btn-ghost text-xl font-bold tracking-wide hover:bg-base-200/50">
                    @if($logo)
                        <img src="{{ Storage::disk(cms_media_disk())->url($logo) }}" alt="{{ $siteName }}" class="h-8 w-auto">
                    @else
                        {{ $siteName }}
                    @endif
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="navbar-center hidden lg:flex">
                <x-menu location="header" style="horizontal" class="flex" />
            </div>

            <div class="navbar-end gap-2 px-2">
                <!-- Premium Custom Language Switcher -->
                @if(view()->exists('theme.theme-moni::components.language-switcher'))
                    @include('theme.theme-moni::components.language-switcher')
                @endif
                
                <!-- Animated theme-moni Custom Theme Switcher -->
                @include('theme.theme-moni::components.theme-switcher')

                <!-- Premium Reservation Button -->
                <a href="https://www.reseliva.com/booknow/Moni-Hotel/?&lang={{ app()->getLocale() }}" target="_blank" class="btn btn-success bg-[#22c55e] hover:bg-[#16a34a] border-[#22c55e] hover:border-[#16a34a] text-white font-semibold shadow-md hidden sm:inline-flex">
                    {{ app()->getLocale() === 'tr' ? 'REZERVASYON' : 'BOOK NOW' }}
                </a>
            </div>
        </div>
    @endif

    {{-- Breadcrumbs --}}
    @if($showBreadcrumbs ?? false)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-tcms::breadcrumbs :items="$breadcrumbItems ?? []" />
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    @php $footerSiteName = \Totoglu\Cms\Models\SiteSetting::getCurrentSiteName(config('app.name')); @endphp
    <footer class="footer footer-center bg-base-200 text-base-content p-10 border-t border-base-300">
        <aside>
            <p class="font-bold text-lg tracking-wider text-primary">{{ $footerSiteName }}</p>
            <p class="text-sm opacity-70">Luxury & Comfort in Harmony</p>
        </aside>
        <nav>
            <x-menu location="footer" style="footer" />
        </nav>
        <aside class="opacity-60 text-xs">
            <p>&copy; {{ date('Y') }} {{ $footerSiteName }}. All rights reserved.</p>
            <x-tcms::powered-by />
        </aside>
    </footer>

    @livewireScripts
    
    {{-- Theme persistence and setup --}}
    <script>
        (function() {
            // Re-apply theme on Livewire navigation transitions
            document.addEventListener('livewire:navigated', function() {
                var savedTheme = localStorage.getItem('tcms-theme');
                if (savedTheme) {
                    document.documentElement.setAttribute('data-theme', savedTheme);
                }
            });
        })();
    </script>

    {{-- Register T-CMS Alpine plugins before Livewire starts Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {
            if (window.__tcmsPlugins?.length) {
                window.__tcmsPlugins.forEach(plugin => window.Alpine.plugin(plugin));
                window.__tcmsPlugins = [];
            }
        });
    </script>
    <x-tcms::code-injection zone="body_end" />
</body>
</html>