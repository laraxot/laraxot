# Implementazione Design Ibrido - Futuur + Prediki

## 🎨 Sistema di Design Ibrido

### 1. **Configurazione Tailwind CSS Ibrida**

#### Tailwind Config
```javascript
// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Palette Ibrida: Futuur (Blu) + Prediki (Verde)
        'hybrid': {
          // Verde Prediki per freschezza e crescita
          'green': {
            50: '#E8F8F5',
            100: '#D1F2E9',
            200: '#A3E5D3',
            300: '#75D8BD',
            400: '#47CBA7',
            500: '#00D4AA', // Prediki Primary
            600: '#00B894', // Prediki Dark
            700: '#009C7E',
            800: '#008068',
            900: '#006452',
          },
          // Blu Futuur per professionalità e fiducia
          'blue': {
            50: '#EFF6FF',
            100: '#DBEAFE',
            200: '#BFDBFE',
            300: '#93C5FD',
            400: '#60A5FA',
            500: '#3B82F6',
            600: '#2563EB',
            700: '#1D4ED8',
            800: '#1E40AF', // Futuur Primary
            900: '#1E3A8A', // Futuur Dark
            950: '#1E293B',
          },
          // Grigi neutri
          'gray': {
            50: '#F8F9FA',
            100: '#E9ECEF',
            200: '#DEE2E6',
            300: '#CED4DA',
            400: '#ADB5BD',
            500: '#6C757D',
            600: '#495057',
            700: '#343A40',
            800: '#212529',
            900: '#0F172A',
          },
        },
      },
      fontFamily: {
        'inter': ['Inter', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        'xl': '12px',
        '2xl': '16px',
        '3xl': '20px',
      },
      boxShadow: {
        'hybrid': '0 2px 8px rgba(0, 0, 0, 0.08)',
        'hybrid-lg': '0 8px 32px rgba(0, 212, 170, 0.15)',
        'hybrid-xl': '0 12px 40px rgba(30, 64, 175, 0.2)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 2. **Componenti Ibridi**

#### Hybrid Prediction Card
```blade
{{-- resources/views/components/hybrid-prediction-card.blade.php --}}
<div class="hybrid-prediction-card group">
    <div class="bg-white rounded-2xl border border-hybrid-gray-200 shadow-hybrid hover:shadow-hybrid-lg transition-all duration-300 hover:-translate-y-2 overflow-hidden">
        {{-- Header con gradient ibrido --}}
        <div class="p-6 border-b border-hybrid-gray-100 bg-gradient-to-r from-hybrid-green-50 via-hybrid-blue-50 to-hybrid-green-50">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-hybrid-gray-900 mb-2 line-clamp-2">
                        {{ $prediction->title }}
                    </h3>
                    <p class="text-sm text-hybrid-gray-600 line-clamp-2">
                        {{ $prediction->description }}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    @if($prediction->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-hybrid-green-50 text-hybrid-green-700 border border-hybrid-green-200">
                            <span class="w-2 h-2 bg-hybrid-green-500 rounded-full mr-2 animate-pulse"></span>
                            Attivo
                        </span>
                    @elseif($prediction->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                            In Attesa
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            Chiuso
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6">
            {{-- Current Price con stile Futuur --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-hybrid-gray-600">Prezzo Attuale</span>
                    <span class="text-3xl font-bold text-hybrid-blue-600">
                        €{{ number_format($prediction->current_price, 2) }}
                    </span>
                </div>
                <div class="flex items-center">
                    @if($prediction->price_change_24h > 0)
                        <svg class="w-5 h-5 text-hybrid-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-semibold text-hybrid-green-600">
                            +{{ number_format($prediction->price_change_24h, 2) }}%
                        </span>
                    @else
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-semibold text-red-600">
                            {{ number_format($prediction->price_change_24h, 2) }}%
                        </span>
                    @endif
                    <span class="text-sm text-hybrid-gray-500 ml-2">24h</span>
                </div>
            </div>

            {{-- Market Stats con stile Prediki --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center p-4 bg-hybrid-green-50 rounded-xl border border-hybrid-green-100">
                    <p class="text-sm text-hybrid-green-600 font-medium mb-1">Volume 24h</p>
                    <p class="text-lg font-bold text-hybrid-gray-900">€{{ number_format($prediction->volume_24h) }}</p>
                </div>
                <div class="text-center p-4 bg-hybrid-blue-50 rounded-xl border border-hybrid-blue-100">
                    <p class="text-sm text-hybrid-blue-600 font-medium mb-1">Partecipanti</p>
                    <p class="text-lg font-bold text-hybrid-gray-900">{{ number_format($prediction->participants_count) }}</p>
                </div>
            </div>

            {{-- Action Buttons ibridi --}}
            <div class="flex space-x-3">
                <a href="{{ route('predict.view', $prediction->slug) }}" 
                   class="flex-1 bg-gradient-to-r from-hybrid-green-500 to-hybrid-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-hybrid-green-600 hover:to-hybrid-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    Visualizza Mercato
                </a>
                @if($prediction->status === 'active')
                    <button class="px-4 py-3 border-2 border-hybrid-green-500 text-hybrid-green-600 font-semibold rounded-xl hover:bg-hybrid-green-500 hover:text-white transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
```

### 3. **Layout Ibrido**

#### Main Layout Ibrido
```blade
{{-- resources/views/layouts/hybrid.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Prediction Market')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        
        .hybrid-gradient {
            background: linear-gradient(135deg, #00D4AA 0%, #1E40AF 100%);
        }
        
        .hybrid-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hybrid-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 212, 170, 0.2);
        }
    </style>
</head>
<body class="h-full bg-hybrid-gray-50">
    <!-- Navigation Ibrida -->
    <nav class="bg-white shadow-hybrid border-b border-hybrid-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Ibrido -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-hybrid-green-500 to-hybrid-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-hybrid-green-600 to-hybrid-blue-600 bg-clip-text text-transparent">
                            Prediction Market
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('predictions.index') }}" class="text-hybrid-gray-600 hover:text-hybrid-green-600 font-medium transition-colors">
                        Mercati
                    </a>
                    <a href="{{ route('portfolio') }}" class="text-hybrid-gray-600 hover:text-hybrid-blue-600 font-medium transition-colors">
                        Portfolio
                    </a>
                    <a href="{{ route('leaderboard') }}" class="text-hybrid-gray-600 hover:text-hybrid-green-600 font-medium transition-colors">
                        Classifica
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-hybrid-gray-700 hover:text-hybrid-gray-900">
                                <div class="w-8 h-8 bg-gradient-to-r from-hybrid-green-100 to-hybrid-blue-100 rounded-full flex items-center justify-center border-2 border-hybrid-green-200">
                                    <span class="text-sm font-bold text-hybrid-green-600">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-hybrid-lg border border-hybrid-gray-200 py-1 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-hybrid-gray-700 hover:bg-hybrid-green-50 hover:text-hybrid-green-600">
                                    Profilo
                                </a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-hybrid-gray-700 hover:bg-hybrid-blue-50 hover:text-hybrid-blue-600">
                                    Impostazioni
                                </a>
                                <hr class="my-1 border-hybrid-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-hybrid-gray-700 hover:bg-red-50 hover:text-red-600">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-hybrid-gray-600 hover:text-hybrid-green-600 font-medium transition-colors">
                            Accedi
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-hybrid-green-500 to-hybrid-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            Registrati
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer Ibrido -->
    <footer class="bg-white border-t border-hybrid-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold bg-gradient-to-r from-hybrid-green-600 to-hybrid-blue-600 bg-clip-text text-transparent mb-4">
                        Prediction Market
                    </h3>
                    <p class="text-hybrid-gray-600 text-sm">
                        Piattaforma innovativa per il trading di prediction markets. 
                        Combina la freschezza del design moderno con la professionalità del trading avanzato.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-hybrid-gray-900 mb-4">Prodotti</h4>
                    <ul class="space-y-2 text-sm text-hybrid-gray-600">
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">Mercati</a></li>
                        <li><a href="#" class="hover:text-hybrid-blue-600 transition-colors">Portfolio</a></li>
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-hybrid-gray-900 mb-4">Supporto</h4>
                    <ul class="space-y-2 text-sm text-hybrid-gray-600">
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">Centro Aiuto</a></li>
                        <li><a href="#" class="hover:text-hybrid-blue-600 transition-colors">Contatti</a></li>
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-hybrid-gray-900 mb-4">Legale</h4>
                    <ul class="space-y-2 text-sm text-hybrid-gray-600">
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">Termini</a></li>
                        <li><a href="#" class="hover:text-hybrid-blue-600 transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-hybrid-green-600 transition-colors">Cookie</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-hybrid-gray-200 text-center">
                <p class="text-sm text-hybrid-gray-600">
                    &copy; {{ date('Y') }} Prediction Market. Tutti i diritti riservati.
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
```

## 🎯 Principi di Implementazione Ibrida

### 1. **Design System Unificato**
- Palette colori che combina verde Prediki e blu Futuur
- Tipografia Inter per leggibilità ottimale
- Sistema di spacing consistente
- Componenti riutilizzabili

### 2. **Performance Ottimizzate**
- CSS critico inline
- Lazy loading per componenti pesanti
- Ottimizzazione immagini
- Minificazione assets

### 3. **Accessibilità**
- Contrasto sufficiente (4.5:1)
- Focus indicators visibili
- Supporto screen readers
- Navigazione da tastiera

### 4. **Mobile-First**
- Design responsive
- Touch-friendly controls
- Performance mobile ottimizzate
- Progressive enhancement

Questa implementazione ibrida fornisce una base solida per creare una piattaforma unica che combina il meglio di entrambi i design, posizionandoci come leader innovativo nel settore dei prediction markets. 