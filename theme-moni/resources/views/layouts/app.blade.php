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

    @if (file_exists(public_path('fonts/filament/filament/inter/index.css')))
        <link rel="stylesheet" href="{{ asset('fonts/filament/filament/inter/index.css') }}" />
    @endif

    <!-- CMS Core Runtime (shared Alpine components) -->
    @tcmsCoreJs
    <!-- Theme Assets -->
    @themeVite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <x-tcms::code-injection zone="head" />
</head>

<body class="min-h-screen bg-base-100 text-base-content font-sans antialiased">
    <x-tcms::code-injection zone="body_start" />
    @php
        $bookingUrl = 'https://www.reseliva.com/booknow/Moni-Hotel/?&lang=' . app()->getLocale();
    @endphp

    @if (!($minimalChrome ?? false))
        @if (function_exists('mega_menu_header_active') && mega_menu_header_active('header'))
            <x-dynamic-component component="mega-menu::header" location="header" />
        @elseif(function_exists('pro_header_active') && pro_header_active('header'))
            <x-dynamic-component component="tcms-pro::full-header" location="header" />
        @else
            {!! tcms_placeholder('header') !!}
        @endif
    @endif

    <!-- Main Content -->
    <main class="w-full">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    @if (!($minimalChrome ?? false))
        {!! tcms_placeholder('footer') !!}
    @endif

    @if (!($minimalChrome ?? false))
        @php
            $isTr = app()->getLocale() === 'tr';

            $speedDialAction = static fn(string $icon, string $url, string $label, bool $newTab = false): array => [
                'type' => 'block',
                'attrs' => [
                    'id' => 'button_item',
                    'config' => [
                        'content' => [
                            'icon' => $icon,
                            'icon_only' => true,
                            'link' => [
                                'type' => 'external_url',
                                'external_url' => $url,
                                'text' => $label,
                                'style' => 'btn-ghost',
                                'size' => 'btn-sm',
                                'outline' => false,
                                'block' => false,
                                'new_tab' => $newTab,
                            ],
                        ],
                    ],
                ],
            ];

            $speedDial = [
                'type' => 'block',
                'attrs' => [
                    'id' => 'speed_dial',
                    'config' => [
                        'content' => [
                            'trigger_label' => $isTr ? 'Hızlı Aksiyonlar' : 'Quick Actions',
                        ],
                        'settings' => [
                            'position' => 'bottom-right',
                            'open_direction' => 'up',
                            'hover_open' => true,
                            'trigger_style' => 'btn-primary',
                            'trigger_size' => 'btn-md',
                        ],
                        'children' => [
                            $speedDialAction('phone', 'tel:00905550679232', $isTr ? 'Ara' : 'Call'),
                            $speedDialAction(
                                'whatsapp',
                                'https://api.whatsapp.com/send?phone=905550679232',
                                'WhatsApp',
                                true,
                            ),
                            $speedDialAction('mail', 'mailto:info@moniotel.com', $isTr ? 'E-posta' : 'Email'),
                            $speedDialAction(
                                'location',
                                'https://www.google.com/maps/search/?api=1&query=36.851227,28.25955399999998',
                                $isTr ? 'Konum' : 'Location',
                                true,
                            ),
                            $speedDialAction(
                                'tripadvisor',
                                'https://www.tripadvisor.com/Hotel_Review-g298033-d5502343-Reviews-Moni_Hotel-Marmaris_Mugla_Province_Turkish_Aegean_Coast.html',
                                'TripAdvisor',
                                true,
                            ),
                            $speedDialAction(
                                'facebook',
                                'https://www.facebook.com/Moni-Hotel-837551173082578/',
                                'Facebook',
                                true,
                            ),
                            $speedDialAction(
                                'instagram',
                                'https://www.instagram.com/moniotel_marmaris/',
                                'Instagram',
                                true,
                            ),
                            $speedDialAction(
                                'booking',
                                'https://www.booking.com/hotel/tr/moni.en-gb.html?label=gen173nr-1FCAEoggJCAlhYSDNYBGhGiAEBmAEowgEDYWJuyAEM2AEB6AEB-AELkgIBeagCBA;sid=f576b1982a51744f54805f0c277a9b55;dest_id=-764696;dest_type=city;dist=0;group_adults=2;hapos=1;hpos=1;room1=A%2CA;sb_price_type=total;srepoch=1517262016;srfid=9438dc5e01d4cc877caad70ace3f986663f84c04X1;srpvid=8d519860c3ea006e;type=total;ucfs=1#hotelTmpl',
                                'Booking.com',
                                true,
                            ),
                        ],
                    ],
                ],
            ];

            echo \Totoglu\Blocks\Support\BlocksRenderer::make([$speedDial])->toUnsafeHtml();
        @endphp
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
