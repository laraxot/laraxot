@props([
    'companyName' => config('app.name'),
    'address' => '',
    'email' => '',
    'phone' => '',
    'vatNumber' => '',
    'showPrivacy' => true,
    'showTerms' => true,
    'showCookie' => true,
])

<footer class="footer-institutional bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ $companyName }}</h3>
                @if($address)
                    <p class="text-gray-400 text-sm">{{ $address }}</p>
                @endif
                
                @if($email || $phone)
                    <div class="space-y-2 text-sm">
                        @if($email)
                            <a href="mailto:{{ $email }}" class="text-gray-400 hover:text-white transition-colors">
                                {{ $email }}
                            </a>
                        @endif
                        
                        @if($phone)
                            <a href="tel:{{ $phone }}" class="text-gray-400 hover:text-white transition-colors">
                                {{ $phone }}
                            </a>
                        @endif
                    </div>
                @endif
                
                @if($vatNumber)
                    <p class="text-gray-400 text-sm">
                        {{ __('P.IVA') }}: {{ $vatNumber }}
                    </p>
                @endif
            </div>
            
            <!-- Quick Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ __('Link Utili') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.services') }}" class="text-gray-400 hover:text-white transition-colors">
                            {{ __('Servizi') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.about') }}" class="text-gray-400 hover:text-white transition-colors">
                            {{ __('Chi Siamo') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.contacts') }}" class="text-gray-400 hover:text-white transition-colors">
                            {{ __('Contatti') }}
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Legal Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ __('Informazioni Legali') }}</h3>
                <ul class="space-y-2 text-sm">
                    @if($showPrivacy && Route::has('pages.view'))
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" class="text-gray-400 hover:text-white transition-colors">
                                {{ __('Privacy Policy') }}
                            </a>
                        </li>
                    @endif
                    
                    @if($showTerms && Route::has('pages.view'))
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'terms']) }}" class="text-gray-400 hover:text-white transition-colors">
                                {{ __('Termini e Condizioni') }}
                            </a>
                        </li>
                    @endif
                    
                    @if($showCookie && Route::has('pages.view'))
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'cookie']) }}" class="text-gray-400 hover:text-white transition-colors">
                                {{ __('Cookie Policy') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>
                &copy; {{ date('Y') }} {{ $companyName }}. 
                {{ __('Tutti i diritti riservati.') }}
            </p>
        </div>
    </div>
</footer>