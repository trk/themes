<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ daisyui_default_preset() }}" data-default-theme="{{ daisyui_default_preset() }}">
<head>
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
        body {
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <x-tcms::code-injection zone="head" />
</head>
<body class="min-h-screen bg-base-100 text-base-content font-sans antialiased">
    <x-tcms::code-injection zone="body_start" />
    @php
        $logo = \Totoglu\Cms\Models\SiteSetting::get('logo');
        $siteName = \Totoglu\Cms\Models\SiteSetting::getCurrentSiteName(config('app.name'));
        $phone = (string) (\Totoglu\Cms\Models\SiteSetting::get('contact_phone') ?? '');
        $email = (string) (\Totoglu\Cms\Models\SiteSetting::get('contact_email') ?? '');
        $isHomepage = isset($seoPage) && is_object($seoPage) && method_exists($seoPage, 'isHomepage') ? (bool) $seoPage->isHomepage() : false;
        $headerOverlay = $isHomepage;
        $bookingUrl = "https://www.reseliva.com/booknow/Moni-Hotel/?&lang=".app()->getLocale();
    @endphp

    @if (! ($minimalChrome ?? false))
        @if(function_exists('mega_menu_header_active') && mega_menu_header_active('header'))
            <x-dynamic-component component="mega-menu::header" location="header" />
        @elseif(function_exists('pro_header_active') && pro_header_active('header'))
            <x-dynamic-component component="tcms-pro::full-header" location="header" />
        @else
            <header @class([
                'z-50 w-full transition-colors duration-300',
                'fixed top-0 left-0 right-0' => $headerOverlay,
                'sticky top-0' => ! $headerOverlay,
            ])>
                <div @class([
                    'border-b',
                    'border-base-200/50 bg-base-100/70 backdrop-blur-xl' => $headerOverlay,
                    'border-base-300 bg-base-100/95 backdrop-blur-md' => ! $headerOverlay,
                ])>
                    <div class="moni-container">
                        <div class="flex items-center justify-between gap-3 py-3">
                            <div class="flex items-center gap-3">
                                <div class="dropdown lg:hidden">
                                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </div>
                                    <div tabindex="0" class="dropdown-content mt-3 z-[1] w-72 rounded-2xl border border-base-300/80 bg-base-100/95 p-3 shadow-xl backdrop-blur-md">
                                        <x-menu location="header" style="vertical" />
                                        <div class="divider my-2"></div>
                                        <div class="flex items-center justify-between gap-3">
                                            @if(view()->exists('theme.theme-moni::components.language-switcher'))
                                                @include('theme.theme-moni::components.language-switcher')
                                            @endif
                                            <a href="{{ $bookingUrl }}" target="_blank" class="btn btn-primary btn-sm">
                                                {{ app()->getLocale() === 'tr' ? 'Rezervasyon' : 'Book' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ tcms_home_url() }}" class="inline-flex items-center gap-3">
                                    @if($logo)
                                        <img src="{{ Storage::disk(cms_media_disk())->url($logo) }}" alt="{{ $siteName }}" class="h-9 w-auto">
                                    @else
                                        <span class="text-base font-semibold tracking-wide">{{ $siteName }}</span>
                                    @endif
                                </a>
                            </div>

                            <nav class="hidden lg:flex items-center justify-center">
                                <x-menu location="header" style="horizontal" class="flex" />
                            </nav>

                            <div class="flex items-center gap-2">
                                <div class="hidden xl:flex items-center gap-2 text-xs text-base-content/70">
                                    @if($phone !== '')
                                        <a class="hover:text-primary transition-colors" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                                    @endif
                                    @if($phone !== '' && $email !== '')
                                        <span class="opacity-40">•</span>
                                    @endif
                                    @if($email !== '')
                                        <a class="hover:text-primary transition-colors" href="mailto:{{ $email }}">{{ $email }}</a>
                                    @endif
                                </div>

                                @if(view()->exists('theme.theme-moni::components.language-switcher'))
                                    <div class="hidden lg:block">
                                        @include('theme.theme-moni::components.language-switcher')
                                    </div>
                                @endif

                                <a href="{{ $bookingUrl }}" target="_blank" class="btn btn-primary hidden sm:inline-flex">
                                    {{ app()->getLocale() === 'tr' ? 'Rezervasyon' : 'Book Now' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @endif
    @endif

    {{-- Breadcrumbs --}}
    @if(! ($minimalChrome ?? false) && ($showBreadcrumbs ?? false))
        <div class="moni-container mt-6">
            <x-tcms::breadcrumbs :items="$breadcrumbItems ?? []" />
        </div>
    @endif

    <!-- Main Content -->
    <main @class([
        'w-full',
        'pt-20' => ! ($minimalChrome ?? false) && $headerOverlay,
    ])>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    @if (! ($minimalChrome ?? false))
        <footer class="border-t border-base-300 bg-base-200/60">
            <div class="moni-container py-12">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-4">
                        <div class="flex items-center gap-3">
                            @if($logo)
                                <img src="{{ Storage::disk(cms_media_disk())->url($logo) }}" alt="{{ $siteName }}" class="h-10 w-auto">
                            @endif
                            <div>
                                <div class="text-base font-semibold tracking-wide">{{ $siteName }}</div>
                                <div class="text-sm text-base-content/70">{{ \Totoglu\Cms\Models\SiteSetting::get('site_tagline', '') }}</div>
                            </div>
                        </div>

                        <div class="mt-5 space-y-1 text-sm text-base-content/70">
                            @if($phone !== '')
                                <div><a class="hover:text-primary transition-colors" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></div>
                            @endif
                            @if($email !== '')
                                <div><a class="hover:text-primary transition-colors" href="mailto:{{ $email }}">{{ $email }}</a></div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ $bookingUrl }}" target="_blank" class="btn btn-primary">
                                {{ app()->getLocale() === 'tr' ? 'Rezervasyon Yap' : 'Book Now' }}
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="text-sm font-semibold tracking-wide">{{ app()->getLocale() === 'tr' ? 'Menü' : 'Menu' }}</div>
                        <div class="mt-3">
                            <x-menu location="footer" style="footer" />
                        </div>
                    </div>

                    <div class="lg:col-span-3">
                        <div class="text-sm font-semibold tracking-wide">Moni Apartment</div>
                        <p class="mt-3 text-sm text-base-content/70">
                            {{ app()->getLocale() === 'tr' ? 'Moni Apartment için ayrı bir konaklama deneyimi.' : 'A separate stay experience by Moni owners.' }}
                        </p>
                        <a class="mt-4 inline-flex text-sm font-semibold text-primary hover:underline" href="{{ url('/apartments') }}">
                            {{ app()->getLocale() === 'tr' ? 'Detaylar' : 'Explore' }}
                        </a>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-2 border-t border-base-300/70 pt-6 text-xs text-base-content/60 sm:flex-row sm:items-center sm:justify-between">
                    <div>&copy; {{ date('Y') }} {{ $siteName }}.</div>
                    <div class="hidden sm:block">
                        <x-tcms::powered-by />
                    </div>
                </div>
            </div>
        </footer>
    @endif

    @livewireScripts

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
