@props(['class' => ''])

<!-- Animated Sun/Moon theme switcher -->
<div x-data="{ 
    theme: localStorage.getItem('tcms-theme') || 'silk',
    toggle() {
        this.theme = this.theme === 'silk' ? 'luxury' : 'silk';
        document.documentElement.setAttribute('data-theme', this.theme);
        localStorage.setItem('tcms-theme', this.theme);
    }
}" x-init="$watch('theme', val => {
    document.documentElement.setAttribute('data-theme', val);
    localStorage.setItem('tcms-theme', val);
})" class="flex items-center {{ $class }}">
    <button @click="toggle()" class="btn btn-ghost btn-circle relative overflow-hidden focus:outline-none hover:bg-base-200/50" aria-label="Toggle Theme">
        <!-- Sun Icon (Visible when theme is dark/luxury) -->
        <span x-show="theme === 'luxury'" 
              x-transition:enter="transition ease-out duration-300 transform"
              x-transition:enter-start="opacity-0 rotate-90 scale-50"
              x-transition:enter-end="opacity-100 rotate-0 scale-100"
              x-transition:leave="transition ease-in duration-200 transform"
              x-transition:leave-start="opacity-100 rotate-0 scale-100"
              x-transition:leave-end="opacity-0 -rotate-90 scale-50"
              class="absolute inset-0 flex items-center justify-center">
            <svg class="w-5.5 h-5.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
            </svg>
        </span>
        
        <!-- Moon Icon (Visible when theme is light/silk) -->
        <span x-show="theme === 'silk'" 
              x-transition:enter="transition ease-out duration-300 transform"
              x-transition:enter-start="opacity-0 -rotate-90 scale-50"
              x-transition:enter-end="opacity-100 rotate-0 scale-100"
              x-transition:leave="transition ease-in duration-200 transform"
              x-transition:leave-start="opacity-100 rotate-0 scale-100"
              x-transition:leave-end="opacity-0 rotate-90 scale-50"
              class="absolute inset-0 flex items-center justify-center">
            <svg class="w-5.5 h-5.5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </span>
    </button>
</div>
