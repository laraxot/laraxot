{{-- Cookie Bar conforme GDPR italiano --}}
<div 
    x-data="{
        show: {{ $show ?? 'true' }},
        accepted: localStorage.getItem('cookieConsent') === 'accepted',
        
        acceptAll() {
            localStorage.setItem('cookieConsent', 'accepted');
            this.show = false;
            this.$dispatch('cookie-accepted');
        },
        
        openPreferences() {
            this.$dispatch('open-cookie-preferences');
            this.show = false;
        }
    }"
    x-show="show && !accepted"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-y-full"
    x-transition:enter-end="transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform translate-y-0"
    x-transition:leave-end="transform translate-y-full"
    class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white z-50 shadow-2xl"
    role="dialog"
    aria-labelledby="cookie-title"
    aria-describedby="cookie-description"
>
    <div class="container-italia mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <h3 id="cookie-title" class="text-lg font-semibold mb-2">
                    {{ __('pub_theme::cookies.title') }}
                </h3>
                
                <p id="cookie-description" class="text-sm leading-relaxed">
                    {{ __('pub_theme::cookies.description') }}
                    <a 
                        href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
                        class="text-blue-400 hover:text-blue-300 underline ml-1"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {{ __('pub_theme::cookies.learn_more') }}
                    </a>
                </p>
            </div>
            
            <div class="flex gap-3 flex-shrink-0">
                <button
                    @click="openPreferences"
                    class="px-4 py-2 border border-blue-600 text-blue-400 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                    :aria-label="__('pub_theme::cookies.customize')"
                >
                    {{ __('pub_theme::cookies.customize') }}
                </button>
                
                <button
                    @click="acceptAll"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                    :aria-label="__('pub_theme::cookies.accept_all')"
                >
                    {{ __('pub_theme::cookies.accept_all') }}
                </button>
                </button>
                
                <button
                    @click="acceptAll"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                    :aria-label="__('pub_theme::cookies.accept_all')"
                >
                    {{ __('pub_theme::cookies.accept_all') }}
                    :aria-label="__('pub_theme::cookies.accept_all')"
                >
                    {{ __('pub_theme::cookies.accept_all') }}
                    :aria-label="__('pub_theme::cookies.accept_all')"
                >
                    {{ __('pub_theme::cookies.accept_all') }}
                </button>
            </div>
        </div>
    </div>
</div>