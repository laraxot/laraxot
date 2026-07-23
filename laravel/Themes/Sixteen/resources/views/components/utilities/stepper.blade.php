{{-- Bootstrap Italia Stepper Component --}}
{{-- 
    Componente stepper conforme alle specifiche Bootstrap Italia
    Supporta processi multi-step, navigazione e accessibilità WCAG 2.1
--}}
@props([
    'steps' => [],
    'currentStep' => 1,
    'variant' => 'horizontal', // 'horizontal', 'vertical'
    'size' => 'md', // 'sm', 'md', 'lg'
    'navigable' => false,
    'showDescription' => true,
    'showNumbers' => true,
    'theme' => 'primary', // 'primary', 'secondary'
    'nonLinear' => false, // Permette di saltare step
])

@php
// Validazione props
$steps = collect($steps)->map(function ($step, $index) {
    if (is_string($step)) {
        return [
            'title' => $step,
            'description' => null,
            'completed' => false,
            'disabled' => false,
            'optional' => false,
        ];
    }
    
    return array_merge([
        'title' => 'Step ' . ($index + 1),
        'description' => null,
        'completed' => false,
        'disabled' => false,
        'optional' => false,
    ], $step);
});

$totalSteps = $steps->count();
$currentStep = max(1, min($currentStep, $totalSteps));

// Classi per tema
$themeClasses = [
    'primary' => [
        'active' => 'bg-italia-blue-500 text-white border-italia-blue-500',
        'completed' => 'bg-italia-green-500 text-white border-italia-green-500',
        'inactive' => 'bg-gray-100 text-gray-500 border-gray-300',
        'disabled' => 'bg-gray-50 text-gray-300 border-gray-200',
        'connector' => 'border-gray-300',
        'connector-active' => 'border-italia-blue-500',
        'connector-completed' => 'border-italia-green-500',
    ],
    'secondary' => [
        'active' => 'bg-gray-600 text-white border-gray-600',
        'completed' => 'bg-italia-green-500 text-white border-italia-green-500',
        'inactive' => 'bg-gray-100 text-gray-500 border-gray-300',
        'disabled' => 'bg-gray-50 text-gray-300 border-gray-200',
        'connector' => 'border-gray-300',
        'connector-active' => 'border-gray-600',
        'connector-completed' => 'border-italia-green-500',
    ],
];

// Classi per dimensione
$sizeClasses = [
    'sm' => [
        'circle' => 'w-8 h-8 text-sm',
        'title' => 'text-sm',
        'description' => 'text-xs',
        'spacing' => 'space-x-4',
        'connector' => 'border-t-2'
    ],
    'md' => [
        'circle' => 'w-10 h-10 text-base',
        'title' => 'text-base',
        'description' => 'text-sm',
        'spacing' => 'space-x-6',
        'connector' => 'border-t-2'
    ],
    'lg' => [
        'circle' => 'w-12 h-12 text-lg',
        'title' => 'text-lg',
        'description' => 'text-base',
        'spacing' => 'space-x-8',
        'connector' => 'border-t-4'
    ]
];

$stepStatus = function ($stepIndex) use ($currentStep, $steps) {
    $step = $steps[$stepIndex];
    
    if ($step['disabled']) return 'disabled';
    if ($step['completed'] || $stepIndex + 1 < $currentStep) return 'completed';
    if ($stepIndex + 1 === $currentStep) return 'active';
    
    return 'inactive';
};
@endphp

<div class="w-full" 
     x-data="{ currentStep: {{ $currentStep }} }"
     role="group"
     aria-label="Processo in {{ $totalSteps }} step">
    
    @if($variant === 'horizontal')
        {{-- Stepper Orizzontale --}}
        <div @class([
            'flex',
            'items-start',
            'justify-between',
            'w-full'
        ])>
            @foreach($steps as $index => $step)
                @php
                    $status = $stepStatus($index);
                    $stepNumber = $index + 1;
                    $isClickable = $navigable && !$step['disabled'] && ($nonLinear || $stepNumber <= $currentStep);
                @endphp
                
                <div class="flex flex-col items-center flex-1 relative"
                     @if($isClickable)
                         x-on:click="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                         role="button"
                         tabindex="0"
                         @keydown.enter="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                         @keydown.space.prevent="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                     @endif
                     aria-current="@if($status === 'active')step @else false @endif"
                >
                    {{-- Connettore precedente --}}
                    @if($index > 0)
                        <div @class([
                            'absolute',
                            'top-5',
                            '-left-1/2',
                            'w-full',
                            'h-0',
                            $sizeClasses[$size]['connector'],
                            $themeClasses[$theme][in_array($stepStatus($index - 1), ['completed', 'active']) ? 'connector-completed' : 'connector']
                        ])></div>
                    @endif
                    
                    {{-- Cerchio step --}}
                    <div @class([
                        'flex',
                        'items-center',
                        'justify-center',
                        'rounded-full',
                        'border-2',
                        'font-semibold',
                        'relative',
                        'z-10',
                        'transition-colors',
                        'duration-200',
                        $sizeClasses[$size]['circle'],
                        $themeClasses[$theme][$status],
                        $isClickable ? 'cursor-pointer hover:scale-105' : '',
                        $status === 'disabled' ? 'cursor-not-allowed' : ''
                    ])>
                        @if($status === 'completed')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @elseif($showNumbers)
                            {{ $stepNumber }}
                        @else
                            <div class="w-2 h-2 rounded-full bg-current"></div>
                        @endif
                    </div>
                    
                    {{-- Testo step --}}
                    <div class="mt-3 text-center max-w-xs">
                        <div @class([
                            'font-medium',
                            $sizeClasses[$size]['title'],
                            $status === 'active' ? 'text-italia-blue-600' : '',
                            $status === 'completed' ? 'text-italia-green-600' : '',
                            $status === 'inactive' ? 'text-gray-700' : '',
                            $status === 'disabled' ? 'text-gray-400' : ''
                        ])>
                            {{ $step['title'] }}
                            @if($step['optional'])
                                <span class="text-xs text-gray-500">(opzionale)</span>
                            @endif
                        </div>
                        
                        @if($showDescription && $step['description'])
                            <div @class([
                                'mt-1',
                                $sizeClasses[$size]['description'],
                                $status === 'active' ? 'text-italia-blue-500' : 'text-gray-500',
                                $status === 'disabled' ? 'text-gray-300' : ''
                            ])>
                                {{ $step['description'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
    @else
        {{-- Stepper Verticale --}}
        <div class="space-y-4">
            @foreach($steps as $index => $step)
                @php
                    $status = $stepStatus($index);
                    $stepNumber = $index + 1;
                    $isClickable = $navigable && !$step['disabled'] && ($nonLinear || $stepNumber <= $currentStep);
                @endphp
                
                <div class="flex items-start relative"
                     @if($isClickable)
                         x-on:click="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                         role="button"
                         tabindex="0"
                         @keydown.enter="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                         @keydown.space.prevent="currentStep = {{ $stepNumber }}; $dispatch('step-changed', { step: {{ $stepNumber }} })"
                     @endif
                     aria-current="@if($status === 'active')step @else false @endif"
                >
                    {{-- Linea connettore --}}
                    @if($index < $totalSteps - 1)
                        <div @class([
                            'absolute',
                            'left-5',
                            'top-12',
                            'w-0.5',
                            'h-16',
                            'bg-gray-300',
                            in_array($status, ['completed', 'active']) ? 'bg-italia-green-300' : ''
                        ])></div>
                    @endif
                    
                    {{-- Cerchio step --}}
                    <div @class([
                        'flex',
                        'items-center',
                        'justify-center',
                        'rounded-full',
                        'border-2',
                        'font-semibold',
                        'flex-shrink-0',
                        'transition-colors',
                        'duration-200',
                        $sizeClasses[$size]['circle'],
                        $themeClasses[$theme][$status],
                        $isClickable ? 'cursor-pointer hover:scale-105' : '',
                        $status === 'disabled' ? 'cursor-not-allowed' : ''
                    ])>
                        @if($status === 'completed')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @elseif($showNumbers)
                            {{ $stepNumber }}
                        @else
                            <div class="w-2 h-2 rounded-full bg-current"></div>
                        @endif
                    </div>
                    
                    {{-- Testo step --}}
                    <div class="ml-4 pb-8">
                        <div @class([
                            'font-medium',
                            $sizeClasses[$size]['title'],
                            $status === 'active' ? 'text-italia-blue-600' : '',
                            $status === 'completed' ? 'text-italia-green-600' : '',
                            $status === 'inactive' ? 'text-gray-700' : '',
                            $status === 'disabled' ? 'text-gray-400' : ''
                        ])>
                            {{ $step['title'] }}
                            @if($step['optional'])
                                <span class="text-xs text-gray-500 ml-1">(opzionale)</span>
                            @endif
                        </div>
                        
                        @if($showDescription && $step['description'])
                            <div @class([
                                'mt-1',
                                $sizeClasses[$size]['description'],
                                $status === 'active' ? 'text-italia-blue-500' : 'text-gray-500',
                                $status === 'disabled' ? 'text-gray-300' : ''
                            ])>
                                {{ $step['description'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    {{-- Contenuto step --}}
    {{ $slot }}
</div>

{{-- 
Utilizzo:

<!-- Stepper orizzontale semplice -->
<x-stepper 
    :steps="['Dati personali', 'Documenti', 'Pagamento', 'Conferma']"
    :current-step="2"
/>

<!-- Stepper con descrizioni -->
<x-stepper 
    :steps="[
        ['title' => 'Registrazione', 'description' => 'Crea il tuo account'],
        ['title' => 'Verifica', 'description' => 'Conferma la tua email'],
        ['title' => 'Completamento', 'description' => 'Finalizza la registrazione']
    ]"
    :current-step="2"
    variant="horizontal"
/>

<!-- Stepper verticale navigabile -->
<x-stepper 
    :steps="[
        ['title' => 'Informazioni base', 'completed' => true],
        ['title' => 'Dettagli aggiuntivi', 'description' => 'Informazioni opzionali', 'optional' => true],
        ['title' => 'Revisione', 'disabled' => true],
        ['title' => 'Invio', 'disabled' => true]
    ]"
    :current-step="2"
    variant="vertical"
    navigable
    size="lg"
    x-on:step-changed="console.log('Step cambiato:', $event.detail.step)"
>
    <!-- Contenuto del step corrente -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <template x-if="currentStep === 1">
            <div>Contenuto step 1</div>
        </template>
        <template x-if="currentStep === 2">
            <div>Contenuto step 2</div>
        </template>
        <!-- ... altri step -->
    </div>
</x-stepper>

<!-- Stepper non lineare -->
<x-stepper 
    :steps="['Setup', 'Configurazione', 'Test', 'Deploy']"
    :current-step="1"
    navigable
    non-linear
    theme="secondary"
/>

<!-- Controllo programmatico -->
<div x-data="{ step: 1 }">
    <x-stepper 
        :steps="['Primo', 'Secondo', 'Terzo']"
        x-bind:current-step="step"
    />
    
    <div class="mt-4 space-x-2">
        <button @click="step = Math.max(1, step - 1)" class="btn-outline">Precedente</button>
        <button @click="step = Math.min(3, step + 1)" class="btn-primary">Successivo</button>
    </div>
</div>
--}}