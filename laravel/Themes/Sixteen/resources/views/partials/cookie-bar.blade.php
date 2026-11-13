{{-- Cookie Bar GDPR compliant - Bootstrap Italia inspired, Tailwind + Alpine --}}
@php
    $categories = config('gdpr.cookie.categories', [
        'necessary' => [
            'name' => 'Necessari',
            'description' => 'Cookie tecnici essenziali per il funzionamento del sito',
            'required' => true,
        ],
        'analytics' => [
            'name' => 'Analitici',
            'description' => 'Cookie per analisi statistiche anonime',
            'required' => false,
        ],
        'marketing' => [
            'name' => 'Marketing',
            'description' => 'Cookie per marketing e profilazione',
            'required' => false,
        ],
    ]);
@endphp

<div
    x-data="{
        show: {{ $show ?? 'true' }},
        accepted: localStorage.getItem('cookie_consent') === 'true',
        preferences: {},
        
        init() {
            const stored = localStorage.getItem('cookie_preferences');
            if (stored) {
                this.preferences = JSON.parse(stored);
            } else {
                // Default: solo necessari
                this.preferences = {
                    necessary: true,
                    analytics: false,
                    marketing: false
                };
            }
            // Se già accettato tutto, nascondi
            if (this.accepted) {
                this.show = false;
            }
        },
        
        acceptAll() {
            this.preferences = {
                necessary: true,
                analytics: true,
                marketing: true
            };
            this.saveAndClose();
        },
        
        acceptNecessaryOnly() {
            this.preferences = {
                necessary: true,
                analytics: false,
                marketing: false
            };
            this.saveAndClose();
        },
        
        saveAndClose() {
            localStorage.setItem('cookie_consent', 'true');
            localStorage.setItem('cookie_preferences', JSON.stringify(this.preferences));
            localStorage.setItem('cookie_consent_date', new Date().toISOString());
            
            // Trigger event for GTM / analytics
            this.$dispatch('cookie-consent-updated', { 
                preferences: this.pategories,
                categories: this.preferences 
            });
            
            this.show = false;
        },
        
        openPreferences() {
            this.$dispatch('open-cookie-preferences');
        },
        
        getConsentValue() {
            // Compatibile con statikbe/laravel-cookie-consent
            // 2 = analytics only, 3 = marketing only, true = both, false = none
            const a = this.preferences.analytics;
            const m = this.preferences.marketing;
            if (a && m) return 'true';
            if (a) return '2';
            if (m) return '3';
            return 'false';
        }
    }"
    x-init="init()"
    x-show="show && !accepted"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-y-full opacity-0"
    x-transition:enter-end="transform translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform translate-y-0 opacity-100"
    x-transition:leave-end="transform translate-y-full opacity-0"
    class="fixed bottom-0 left-0 right-0 z-50 shadow-2xl"
    role="dialog"
    aria-labelledby="cookie-title"
    aria-describedby="cookie-description"
    style="background-color: var(--color-neutral-900, #111827); color: var(--color-neutral-50, #f9fafb);"
>
    <div class="max-w-7xl mx-auto px-4 py-4 md:py-3">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <h3 id="cookie-title" class="text-base md:text-lg font-semibold mb-1.5">
                    {{ __('gdpr::cookie.title') }}
                </h3>
                
                <p id="cookie-description" class="text-sm leading-relaxed text-neutral-300">
                    {{ __('gdpr::cookie.description') }}
                    <a 
                        href="{{ url('/pages/privacy') }}"
                        class="text-primary-400 hover:text-primary-300 underline underline-offset-2 transition-colors ml-1"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {{ __('gdpr::cookie.learn_more') }}
                    </a>
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2.5 flex-shrink-0">
                <button
                    @click="openPreferences"
                    class="px-4 py-2 text-sm border border-neutral-600 text-neutral-200 rounded-lg hover:bg-neutral-700 hover:border-primary-500 hover:text-primary-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-neutral-900"
                    :aria-label="__('gdpr::cookie.customize')"
                >
                    {{ __('gdpr::cookie.customize') }}
                </button>
                
                <button
                    @click="acceptNecessaryOnly"
                    class="px-5 py-2 text-sm bg-neutral-700 text-neutral-200 rounded-lg hover:bg-neutral-600 transition-colors focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 focus:ring-offset-neutral-900"
                    :aria-label="__('gdpr::cookie.accept_necessary')"
                >
                    {{ __('gdpr::cookie.accept_necessary') }}
                </button>
                
                <button
                    @click="acceptAll"
                    class="px-5 py-2 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-neutral-900"
                    :aria-label="__('gdpr::cookie.accept_all')"
                >
                    {{ __('gdpr::cookie.accept_all') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Preferenze --}}
<div
    x-data="{
        open: false,
        preferences: {},
        categories: @json($categories),
        
        init() {
            window.addEventListener('open-cookie-preferences', () => {
                const stored = localStorage.getItem('cookie_preferences');
                this.preferences = stored ? JSON.parse(stored) : {
                    necessary: true,
                    analytics: false,
                    marketing: false
                };
                this.open = true;
                document.body.style.overflow = 'hidden';
            });
        },
        
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        
        save() {
            localStorage.setItem('cookie_consent', 'true');
            localStorage.setItem('cookie_preferences', JSON.stringify(this.preferences));
            localStorage.setItem('cookie_consent_date', new Date().toISOString());
            
            this.$dispatch('cookie-consent-updated', { 
                preferences: this.preferences 
            });
            
            this.close();
        },
        
        toggleCategory(category) {
            if (category === 'necessary') return; // Sempre obbligatorio
            this.preferences[category] = !this.preferences[category];
        }
    }"
    x-init="init()"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    @keydown.escape.window="close()"
    aria-labelledby="modal-title"
    aria-modal="true"
    role="dialog"
>
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>
    
    <div class="relative mx-auto my-16 max-w-md px-4">
        <div class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl overflow-hidden"
             style="border: 1px solid var(--color-neutral-200, #e5e7eb);"
             @click.outside="close()"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="border-color: var(--color-neutral-200, #e5e7eb);"
            >
                <h2 id="modal-title" class="text-lg font-semibold text-neutral-900 dark:text-neutral-50">
                    {{ __('gdpr::cookie.modal_title') }}
                </h2>
                <button
                    @click="close"
                    class="p-1 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="{{ __('gdpr::cookie.close_modal') }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ __('gdpr::cookie.modal_description') }}
                </p>
                
                <div class="space-y-4" role="group" aria-labelledby="modal-title">
                    @foreach($categories as $key => $category)
                        <div class="flex items-start justify-between gap-4 p-4 rounded-lg"
                             style="background-color: var(--color-neutral-50, #f9fafb);"
                        >
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-50">
                                        {{ $category['name'] }}
                                    </h3>
                                    @if($category['required'])
                                        <span class="px-2 py-0.5 text-xs font-medium bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 rounded-full">
                                            {{ __('gdpr::cookie.required') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $category['description'] }}
                                </p>
                            </div>
                            
                            <div class="flex-shrink-0">
                                @if($category['required'])
                                    <input
                                        type="checkbox"
                                        checked
                                        disabled
                                        class="w-5 h-5 text-primary-600 border-neutral-300 rounded focus:ring-primary-500 cursor-not-allowed opacity-50"
                                        aria-label="{{ $category['name'] }} {{ __('gdpr::cookie.required') }}"
                                    >
                                @else
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            :checked="preferences['{{ $key }}']"
                                            @change="toggleCategory('{{ $key }}')"
                                            class="sr-only peer"
                                            :aria-label="$category['name']"
                                        >
                                        <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-primary-600"></div>
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="pt-4 border-t"
                     style="border-color: var(--color-neutral-200, #e5e7eb);"
                >
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-3">
                        {{ __('gdpr::cookie.modal_footer') }}
                    </p>
                    <div class="flex gap-3">
                        <button
                            @click="close"
                            class="flex-1 px-4 py-2 text-sm border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        >
                            {{ __('gdpr::cookie.cancel') }}
                        </button>
                        <button
                            @click="save"
                            class="flex-1 px-4 py-2 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        >
                            {{ __('gdpr::cookie.save_preferences') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
