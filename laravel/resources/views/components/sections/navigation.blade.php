@php
use Illuminate\Support\Facades\Route;
$currentPath = Route::current() ? Route::current()->uri() : '';
$locale = app()->getLocale();
@endphp

<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">TechPlanner</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <!-- Services with Mega Menu -->
                <div class="relative group">
                    <button class="flex items-center text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                        Servizi
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 w-screen max-w-4xl bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 mt-2">
                        <div class="grid grid-cols-3 gap-8 p-8">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-4">Radioprotezione</h3>
                                <ul class="space-y-2">
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Controllo Radioprotezione</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Verifica Schermature</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Dosimetria</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Formazione Personale</a></li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-4">Elettromedicali</h3>
                                <ul class="space-y-2">
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Controllo Elettromedicali</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Manutenzione Preventiva</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Calibrazione Strumenti</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Sicurezza Elettrica IEC 62353</a></li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-4">Documentazione</h3>
                                <ul class="space-y-2">
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Registro Apparecchiature</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Documenti Conformità</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Relazioni Tecniche</a></li>
                                    <li><a href="/services" class="text-gray-600 hover:text-blue-600 transition-colors">Consulenza Normativa</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Menu Items -->
                <a href="/services" class="text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    Tutti i Servizi
                </a>
                <a href="/about" class="text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    Chi Siamo
                </a>
                <a href="/blog" class="text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    Blog
                </a>
                <a href="/faq" class="text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    FAQ
                </a>
                <a href="/contacts" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Contatti
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden pb-4">
            <div class="space-y-2">
                <!-- Mobile Services Dropdown -->
                <div>
                    <button onclick="toggleMobileDropdown('services')" class="w-full flex items-center justify-between text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                        Servizi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-services-dropdown" class="hidden pl-4 space-y-2">
                        <a href="/services" class="block text-gray-600 hover:text-blue-600 transition-colors py-2">Controllo Radioprotezione</a>
                        <a href="/services" class="block text-gray-600 hover:text-blue-600 transition-colors py-2">Controllo Elettromedicali</a>
                        <a href="/services" class="block text-gray-600 hover:text-blue-600 transition-colors py-2">Documentazione e Conformità</a>
                        <a href="/services" class="block text-gray-600 hover:text-blue-600 transition-colors py-2">Tutti i Servizi</a>
                    </div>
                </div>
                
                <a href="/about" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    Chi Siamo
                </a>
                <a href="/blog" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    Blog
                </a>
                <a href="/faq" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">
                    FAQ
                </a>
                <a href="/contacts" class="block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-center">
                    Contatti
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
}

function toggleMobileDropdown(id) {
    const dropdown = document.getElementById(`mobile-${id}-dropdown`);
    dropdown.classList.toggle('hidden');
}
</script>