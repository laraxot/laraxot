@php
    use Illuminate\Support\Facades\Auth;
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
@endphp

<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Logout effettuato con successo') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Verrai reindirizzato alla home page tra pochi secondi...') }}
                </p>
            </div>
            <div class="mt-6">
                <a href="{{ route('home') }}"
                   class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Torna alla Home') }}
                </a>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('home') }}";
        }, 3000);
    </script>
</x-layouts.app>
