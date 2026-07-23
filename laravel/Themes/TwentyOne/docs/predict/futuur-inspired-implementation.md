# Implementazione Ispirata a Futuur - Prediction Market

## 🎨 Sistema di Design Futuur-Inspired

### 1. **Configurazione Tailwind CSS**

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
        // Futuur Color Palette
        'futuur': {
          50: '#EFF6FF',
          100: '#DBEAFE',
          200: '#BFDBFE',
          300: '#93C5FD',
          400: '#60A5FA',
          500: '#3B82F6',
          600: '#2563EB',
          700: '#1D4ED8',
          800: '#1E40AF',
          900: '#1E3A8A',
          950: '#1E293B',
        },
        'futuur-gray': {
          50: '#F8FAFC',
          100: '#F1F5F9',
          200: '#E2E8F0',
          300: '#CBD5E1',
          400: '#94A3B8',
          500: '#64748B',
          600: '#475569',
          700: '#334155',
          800: '#1E293B',
          900: '#0F172A',
        },
        'futuur-success': {
          50: '#DCFCE7',
          100: '#BBF7D0',
          500: '#10B981',
          600: '#059669',
          700: '#047857',
        },
        'futuur-error': {
          50: '#FEE2E2',
          100: '#FECACA',
          500: '#EF4444',
          600: '#DC2626',
          700: '#B91C1C',
        },
      },
      fontFamily: {
        'inter': ['Inter', 'system-ui', 'sans-serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      borderRadius: {
        'xl': '12px',
        '2xl': '16px',
      },
      boxShadow: {
        'futuur': '0 1px 3px rgba(0, 0, 0, 0.1)',
        'futuur-lg': '0 4px 12px rgba(0, 0, 0, 0.15)',
        'futuur-xl': '0 8px 25px rgba(0, 0, 0, 0.15)',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-slow': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 2. **Componenti Base Futuur-Style**

#### Prediction Card Component
```blade
{{-- resources/views/components/prediction-card.blade.php --}}
<div class="futuur-prediction-card group">
    <div class="bg-white rounded-xl border border-futuur-gray-200 shadow-futuur hover:shadow-futuur-lg transition-all duration-200 hover:-translate-y-1">
        {{-- Header --}}
        <div class="p-6 border-b border-futuur-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-futuur-gray-900 mb-2 line-clamp-2">
                        {{ $prediction->title }}
                    </h3>
                    <p class="text-sm text-futuur-gray-600 line-clamp-2">
                        {{ $prediction->description }}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    @if($prediction->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-futuur-success-50 text-futuur-success-700">
                            <span class="w-1.5 h-1.5 bg-futuur-success-500 rounded-full mr-1.5"></span>
                            Attivo
                        </span>
                    @elseif($prediction->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                            In Attesa
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-futuur-error-50 text-futuur-error-700">
                            <span class="w-1.5 h-1.5 bg-futuur-error-500 rounded-full mr-1.5"></span>
                            Chiuso
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6">
            {{-- Current Price --}}
            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-futuur-gray-600">Prezzo Attuale</span>
                    <span class="text-2xl font-bold text-futuur-gray-900">€{{ number_format($prediction->current_price, 2) }}</span>
                </div>
                <div class="mt-2 flex items-center">
                    @if($prediction->price_change_24h > 0)
                        <svg class="w-4 h-4 text-futuur-success-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-futuur-success-600">
                            +{{ number_format($prediction->price_change_24h, 2) }}%
                        </span>
                    @else
                        <svg class="w-4 h-4 text-futuur-error-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-futuur-error-600">
                            {{ number_format($prediction->price_change_24h, 2) }}%
                        </span>
                    @endif
                    <span class="text-sm text-futuur-gray-500 ml-2">24h</span>
                </div>
            </div>

            {{-- Market Stats --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <p class="text-sm text-futuur-gray-600">Volume 24h</p>
                    <p class="text-lg font-semibold text-futuur-gray-900">€{{ number_format($prediction->volume_24h) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-futuur-gray-600">Partecipanti</p>
                    <p class="text-lg font-semibold text-futuur-gray-900">{{ number_format($prediction->participants_count) }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex space-x-3">
                <a href="{{ route('predict.view', $prediction->slug) }}" 
                   class="flex-1 bg-gradient-to-r from-futuur-600 to-futuur-700 text-white font-medium py-3 px-4 rounded-lg hover:from-futuur-700 hover:to-futuur-800 transition-all duration-200 transform hover:scale-[1.02] text-center">
                    Visualizza Mercato
                </a>
                @if($prediction->status === 'active')
                    <button class="px-4 py-3 border-2 border-futuur-600 text-futuur-600 font-medium rounded-lg hover:bg-futuur-600 hover:text-white transition-all duration-200">
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

#### Trading Form Component
```blade
{{-- resources/views/components/trading-form.blade.php --}}
<div class="futuur-trading-form">
    <div class="bg-white rounded-xl border border-futuur-gray-200 shadow-futuur">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-futuur-gray-100 bg-futuur-gray-50">
            <h3 class="text-lg font-semibold text-futuur-gray-900">Piazza Ordine</h3>
            <p class="text-sm text-futuur-gray-600">Acquista o vendi quote</p>
        </div>

        {{-- Form --}}
        <div class="p-6">
            <form wire:submit.prevent="placeOrder" class="space-y-6">
                {{-- Order Type Toggle --}}
                <div class="flex rounded-lg border border-futuur-gray-200 p-1 bg-futuur-gray-50">
                    <button type="button" 
                            wire:click="setOrderType('buy')" 
                            class="flex-1 py-2.5 px-4 text-sm font-medium rounded-md transition-all duration-200 {{ $orderType === 'buy' ? 'bg-white text-futuur-600 shadow-sm' : 'text-futuur-gray-600 hover:text-futuur-gray-900' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Acquista
                    </button>
                    <button type="button" 
                            wire:click="setOrderType('sell')" 
                            class="flex-1 py-2.5 px-4 text-sm font-medium rounded-md transition-all duration-200 {{ $orderType === 'sell' ? 'bg-white text-futuur-error-600 shadow-sm' : 'text-futuur-gray-600 hover:text-futuur-gray-900' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Vendi
                    </button>
                </div>

                {{-- Quantity Input --}}
                <div>
                    <label class="block text-sm font-medium text-futuur-gray-700 mb-2">
                        Quantità Quote
                    </label>
                    <div class="relative">
                        <input type="number" 
                               wire:model.live="quantity" 
                               class="w-full px-4 py-3 border-2 border-futuur-gray-200 rounded-lg focus:border-futuur-500 focus:ring-4 focus:ring-futuur-100 transition-all duration-200 text-lg font-medium"
                               placeholder="0" 
                               min="1" 
                               step="1">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <span class="text-futuur-gray-500 text-sm font-medium">quote</span>
                        </div>
                    </div>
                    @error('quantity')
                        <p class="mt-1 text-sm text-futuur-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price Preview --}}
                <div class="bg-gradient-to-r from-futuur-gray-50 to-futuur-50 rounded-lg p-4 border border-futuur-gray-100">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-futuur-gray-600">Prezzo per quota:</span>
                            <span class="text-lg font-semibold {{ $orderType === 'buy' ? 'text-futuur-success-600' : 'text-futuur-error-600' }}">
                                €{{ number_format($currentPrice, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-futuur-gray-600">Costo totale:</span>
                            <span class="text-xl font-bold text-futuur-gray-900">
                                €{{ number_format($totalCost, 2) }}
                            </span>
                        </div>
                        @if($potentialProfit !== null)
                            <div class="flex justify-between items-center pt-2 border-t border-futuur-gray-200">
                                <span class="text-sm text-futuur-gray-600">Profitto potenziale:</span>
                                <span class="text-sm font-semibold {{ $potentialProfit > 0 ? 'text-futuur-success-600' : 'text-futuur-error-600' }}">
                                    {{ $potentialProfit > 0 ? '+' : '' }}€{{ number_format($potentialProfit, 2) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" 
                        class="w-full py-3 px-4 bg-gradient-to-r {{ $orderType === 'buy' ? 'from-futuur-success-500 to-futuur-success-600' : 'from-futuur-error-500 to-futuur-error-600' }} text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $quantity < 1 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                    </svg>
                    {{ $orderType === 'buy' ? 'Acquista Quote' : 'Vendi Quote' }}
                </button>
            </form>
        </div>
    </div>
</div>
```

#### Market Depth Chart Component
```blade
{{-- resources/views/components/market-depth.blade.php --}}
<div class="futuur-market-depth">
    <div class="bg-white rounded-xl border border-futuur-gray-200 shadow-futuur">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-futuur-gray-100 bg-futuur-gray-50">
            <h3 class="text-lg font-semibold text-futuur-gray-900">Profondità di Mercato</h3>
            <p class="text-sm text-futuur-gray-600">Order book in tempo reale</p>
        </div>

        {{-- Content --}}
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                {{-- Buy Orders --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-futuur-success-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Acquisti
                    </h4>
                    <div class="space-y-2">
                        @foreach($buyOrders as $order)
                            <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-futuur-success-50 cursor-pointer transition-colors group"
                                 wire:click="selectPrice({{ $order['price'] }}, 'buy')">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-futuur-success-700">
                                        €{{ number_format($order['price'], 2) }}
                                    </span>
                                    <span class="text-sm text-futuur-gray-600">
                                        {{ number_format($order['quantity']) }}
                                    </span>
                                </div>
                                <div class="w-16 h-2 bg-futuur-success-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-futuur-success-400 to-futuur-success-500 rounded-full transition-all duration-300 group-hover:from-futuur-success-500 group-hover:to-futuur-success-600" 
                                         style="width: {{ ($order['quantity'] / $maxQuantity) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Sell Orders --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-futuur-error-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Vendite
                    </h4>
                    <div class="space-y-2">
                        @foreach($sellOrders as $order)
                            <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-futuur-error-50 cursor-pointer transition-colors group"
                                 wire:click="selectPrice({{ $order['price'] }}, 'sell')">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-futuur-error-700">
                                        €{{ number_format($order['price'], 2) }}
                                    </span>
                                    <span class="text-sm text-futuur-gray-600">
                                        {{ number_format($order['quantity']) }}
                                    </span>
                                </div>
                                <div class="w-16 h-2 bg-futuur-error-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-futuur-error-400 to-futuur-error-500 rounded-full transition-all duration-300 group-hover:from-futuur-error-500 group-hover:to-futuur-error-600" 
                                         style="width: {{ ($order['quantity'] / $maxQuantity) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 3. **Layout Principale Futuur-Style**

#### Main Layout
```blade
{{-- resources/views/layouts/futuur.blade.php --}}
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        
        .futuur-gradient {
            background: linear-gradient(135deg, #1E40AF 0%, #1E3A8A 100%);
        }
        
        .futuur-card-hover {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .futuur-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="h-full bg-futuur-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-futuur-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-futuur-600 to-futuur-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-futuur-gray-900">Prediction Market</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('predictions.index') }}" class="text-futuur-gray-600 hover:text-futuur-gray-900 font-medium transition-colors">
                        Mercati
                    </a>
                    <a href="{{ route('portfolio') }}" class="text-futuur-gray-600 hover:text-futuur-gray-900 font-medium transition-colors">
                        Portfolio
                    </a>
                    <a href="{{ route('leaderboard') }}" class="text-futuur-gray-600 hover:text-futuur-gray-900 font-medium transition-colors">
                        Classifica
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-futuur-gray-700 hover:text-futuur-gray-900">
                                <div class="w-8 h-8 bg-futuur-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-futuur-600">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-futuur-gray-200 py-1 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-futuur-gray-700 hover:bg-futuur-gray-50">
                                    Profilo
                                </a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-futuur-gray-700 hover:bg-futuur-gray-50">
                                    Impostazioni
                                </a>
                                <hr class="my-1 border-futuur-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-futuur-gray-700 hover:bg-futuur-gray-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-futuur-gray-600 hover:text-futuur-gray-900 font-medium transition-colors">
                            Accedi
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-futuur-600 to-futuur-700 text-white px-4 py-2 rounded-lg font-medium hover:from-futuur-700 hover:to-futuur-800 transition-all duration-200">
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

    <!-- Footer -->
    <footer class="bg-white border-t border-futuur-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-futuur-gray-900 mb-4">Prediction Market</h3>
                    <p class="text-futuur-gray-600 text-sm">
                        Piattaforma leader per il trading di prediction markets. 
                        Prevedi il futuro e guadagna dalla tua saggezza collettiva.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-futuur-gray-900 mb-4">Prodotti</h4>
                    <ul class="space-y-2 text-sm text-futuur-gray-600">
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Mercati</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Portfolio</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-futuur-gray-900 mb-4">Supporto</h4>
                    <ul class="space-y-2 text-sm text-futuur-gray-600">
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Centro Aiuto</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Contatti</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-futuur-gray-900 mb-4">Legale</h4>
                    <ul class="space-y-2 text-sm text-futuur-gray-600">
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Termini</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-futuur-gray-900 transition-colors">Cookie</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-futuur-gray-200 text-center">
                <p class="text-sm text-futuur-gray-600">
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

### 4. **CSS Personalizzato Futuur-Style**

#### Custom CSS
```css
/* resources/css/futuur-components.css */

/* Futuur Card Styles */
.futuur-prediction-card {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.futuur-prediction-card:hover {
    transform: translateY(-2px);
}

/* Futuur Button Styles */
.futuur-btn-primary {
    background: linear-gradient(135deg, #1E40AF 0%, #1E3A8A 100%);
    transition: all 0.2s ease;
}

.futuur-btn-primary:hover {
    background: linear-gradient(135deg, #1E3A8A 0%, #1E293B 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

/* Futuur Input Styles */
.futuur-input {
    transition: all 0.2s ease;
}

.futuur-input:focus {
    border-color: #1E40AF;
    box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
}

/* Futuur Loading Animation */
.futuur-loading {
    animation: futuur-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes futuur-pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Futuur Progress Bar */
.futuur-progress {
    background: #E2E8F0;
    border-radius: 9999px;
    overflow: hidden;
}

.futuur-progress-bar {
    background: linear-gradient(90deg, #1E40AF 0%, #3B82F6 100%);
    transition: width 0.3s ease;
}

/* Futuur Status Badges */
.futuur-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.futuur-status-active {
    background: #DCFCE7;
    color: #166534;
}

.futuur-status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.futuur-status-closed {
    background: #FEE2E2;
    color: #991B1B;
}

/* Futuur Chart Styles */
.futuur-chart {
    background: white;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    padding: 24px;
}

.futuur-chart-tooltip {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Futuur Mobile Optimizations */
@media (max-width: 768px) {
    .futuur-container {
        padding: 16px;
    }
    
    .futuur-card {
        margin-bottom: 16px;
    }
    
    .futuur-btn {
        padding: 12px 20px;
        font-size: 16px;
    }
}

/* Futuur Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .futuur-card {
        background: #1E293B;
        border-color: #334155;
        color: #F1F5F9;
    }
    
    .futuur-input {
        background: #1E293B;
        border-color: #334155;
        color: #F1F5F9;
    }
}
```

## 🎯 Principi di Implementazione

### 1. **Design System Coerente**
- Palette colori unificata basata su Futuur
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

Questa implementazione fornisce una base solida per replicare il design moderno e professionale di Futuur.com nel nostro prediction market. 