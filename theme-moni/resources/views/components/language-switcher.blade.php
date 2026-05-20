@props([
    'model' => null,
])

@php
    // Stop rendering if multilingual UX is not active
    if (!function_exists('tcms_multilingual_active') || !tcms_multilingual_active()) {
        return;
    }

    $registry = app(\Totoglu\Cms\Services\LocaleRegistry::class);
    $currentLocale = app()->getLocale();
    $locales = $registry->getLocales();

    // Dynamically resolve alternate URLs for page/model
    $alternateUrls = [];
    if ($model && method_exists($model, 'getTranslation')) {
        $alternateUrls = tcms_alternate_urls($model);
    } else {
        foreach ($locales as $code => $locale) {
            $alternateUrls[$code] = tcms_localized_url(tcms_current_slug(), $code);
        }
    }

    $currentLocaleData = $locales[$currentLocale] ?? null;
@endphp

@if(count($locales) > 1)
    <div x-data="{ open: false }" class="relative inline-block text-left" @click.away="open = false">
        <!-- Trigger Button -->
        <button
            @click="open = !open"
            type="button"
            class="group inline-flex items-center gap-2 rounded-full border border-base-300 bg-base-100/80 px-3.5 py-1.5 text-sm font-medium text-base-content/85 shadow-sm hover:shadow-md backdrop-blur-sm transition-all duration-300 hover:border-primary/30 hover:bg-primary/5 hover:text-primary focus:outline-none"
            aria-expanded="false"
            aria-haspopup="true"
        >
            <!-- Globe Icon -->
            <svg class="w-4 h-4 text-base-content/60 group-hover:text-primary transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
            
            <!-- Active Locale Code -->
            <span class="uppercase tracking-wider font-semibold text-xs">
                {{ $currentLocale }}
            </span>

            <!-- Arrow Icon -->
            <svg class="w-3.5 h-3.5 opacity-60 group-hover:text-primary transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
            class="absolute right-0 z-50 mt-2.5 w-44 origin-top-right rounded-xl border border-base-300/80 bg-base-100/95 p-1.5 shadow-xl backdrop-blur-md focus:outline-none"
            style="display: none;"
            role="menu"
            aria-orientation="vertical"
        >
            <div class="py-0.5" role="none">
                @foreach($locales as $code => $locale)
                    @php $url = $alternateUrls[$code] ?? tcms_localized_url(tcms_current_slug(), $code); @endphp
                    <a
                        href="{{ url($url) }}"
                        class="group flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $code === $currentLocale ? 'bg-primary/10 text-primary' : 'text-base-content/75 hover:bg-base-200 hover:text-base-content' }}"
                        role="menuitem"
                        @if($code === $currentLocale) aria-current="true" @endif
                        hreflang="{{ \Totoglu\Cms\Services\LocaleRegistry::toBcp47($code) }}"
                    >
                        <span>{{ $locale['native'] }}</span>
                        
                        @if($code === $currentLocale)
                            <!-- Gold Checkmark for active -->
                            <svg class="h-4.5 w-4.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
