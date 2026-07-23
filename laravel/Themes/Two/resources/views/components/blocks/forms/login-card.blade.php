@props([
    'title' => 'Login',
    'subtitle' => '',
    'livewireComponent' => null,
])

<div class="login-card bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
    <div class="login-card-header mb-6">
        @if($title)
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
        @endif
        
        @if($subtitle)
            <p class="text-gray-600 text-sm">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="login-card-body">
        @if($livewireComponent)
            @livewire($livewireComponent)
        @endif
        
        {{ $slot }}
    </div>
</div>

<style>
.login-card {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-card-header h2 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.login-card-body {
    padding: 0;
}
</style>