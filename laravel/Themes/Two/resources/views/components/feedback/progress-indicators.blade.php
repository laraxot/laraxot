@props([
    'steps' => [],
    'currentStep' => 0,
    'showLabels' => true,
])

@if(!empty($steps))
<div class="progress-indicators" aria-label="Progresso">
    <div class="flex items-center justify-between">
        @foreach($steps as $index => $step)
            <div class="flex items-center flex-1 {{ $index < count($steps) - 1 ? 'pr-8' : '' }}">
                <!-- Step Number/Circle -->
                <div 
                    class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2 transition-colors
                        {{ $index < $currentStep 
                            ? 'bg-primary-600 border-primary-600 text-white' 
                            : ($index === $currentStep 
                                ? 'border-primary-600 text-primary-600' 
                                : 'border-gray-300 text-gray-400') }}"
                    aria-current="{{ $index === $currentStep ? 'step' : 'false' }}"
                >
                    @if($index < $currentStep)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <span class="text-sm font-medium">{{ $index + 1 }}</span>
                    @endif
                </div>
                
                <!-- Step Label -->
                @if($showLabels && isset($step['label']))
                    <div class="ml-3">
                        <p class="text-sm font-medium 
                            {{ $index <= $currentStep ? 'text-gray-900' : 'text-gray-400' }}">
                            {{ $step['label'] }}
                        </p>
                        @if(isset($step['description']))
                            <p class="text-xs text-gray-500">{{ $step['description'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Connecting Line -->
            @if($index < count($steps) - 1)
                <div class="flex-1 h-0.5 mx-4 
                    {{ $index < $currentStep ? 'bg-primary-600' : 'bg-gray-300' }}">
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif

<style>
.progress-indicators {
    padding: 1.5rem 0;
}

.step-indicator {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .progress-indicators .flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .progress-indicators .flex-1 {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .progress-indicators .h-0\.5 {
        display: none;
    }
}
</style>