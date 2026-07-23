@props(['data' => []])

{{-- 
    Steps Block
    Usage: <x-blocks.steps.steps :data="$stepsData" />
    
    Data structure:
    - title: string
    - steps: array of ['number' => int, 'title' => string, 'description' => string, 'icon' => string (optional)]
    - layout: 'vertical' | 'horizontal'
    - interactive: boolean (default: false)
--}}

@php
    $layout = $data['layout'] ?? 'vertical';
    $title = $data['title'] ?? '';
    $steps = $data['steps'] ?? [];
    $interactive = $data['interactive'] ?? false;
@endphp

<div class="steps-block {{ $layout === 'horizontal' ? 'steps-horizontal' : 'steps-vertical' }}">
    @if($title)
    <h3 class="steps-title text-xl font-semibold mb-6 text-gray-900">
        {{ $title }}
    </h3>
    @endif
    
    @if($layout === 'horizontal')
        {{-- Horizontal Steps Layout --}}
        <div class="steps-horizontal-wrapper flex flex-wrap gap-4">
            @foreach($steps as $index => $step)
            <div class="step-item flex-1 min-w-[200px]">
                <div class="step-content">
                    {{-- Step Number --}}
                    <div class="step-number w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg mb-3">
                        {{ $step['number'] ?? ($index + 1) }}
                    </div>
                    
                    {{-- Step Icon (if provided) --}}
                    @if(isset($step['icon']))
                    <div class="step-icon mb-3">
                        <x-filament::icon 
                            :icon="$step['icon']" 
                            class="icon-lg icon-primary" 
                            aria-hidden="true" 
                        />
                    </div>
                    @endif
                    
                    {{-- Step Title --}}
                    <h4 class="step-title text-base font-semibold text-gray-900 mb-2">
                        {{ $step['title'] }}
                    </h4>
                    
                    {{-- Step Description --}}
                    @if(isset($step['description']))
                    <p class="step-description text-sm text-gray-600">
                        {{ $step['description'] }}
                    </p>
                    @endif
                </div>
                
                {{-- Connector Line (except last step) --}}
                @if(!$loop->last)
                <div class="step-connector hidden md:block absolute top-5 left-full w-full h-0.5 bg-gray-300 -z-10"></div>
                @endif
            </div>
            @endforeach
        </div>
        
    @else
        {{-- Vertical Steps Layout --}}
        <div class="steps-vertical-wrapper space-y-6">
            @foreach($steps as $index => $step)
            <div class="step-item flex gap-4">
                {{-- Step Number --}}
                <div class="step-number-wrapper flex-shrink-0">
                    <div class="step-number w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                        {{ $step['number'] ?? ($index + 1) }}
                    </div>
                    
                    {{-- Connector Line (except last step) --}}
                    @if(!$loop->last)
                    <div class="step-connector w-0.5 h-full bg-gray-300 my-2"></div>
                    @endif
                </div>
                
                {{-- Step Content --}}
                <div class="step-content flex-1">
                    {{-- Step Icon (if provided) --}}
                    @if(isset($step['icon']))
                    <div class="step-icon mb-2">
                        <x-filament::icon 
                            :icon="$step['icon']" 
                            class="icon-md icon-primary" 
                            aria-hidden="true" 
                        />
                    </div>
                    @endif
                    
                    {{-- Step Title --}}
                    <h4 class="step-title text-base font-semibold text-gray-900 mb-2">
                        {{ $step['title'] }}
                    </h4>
                    
                    {{-- Step Description --}}
                    @if(isset($step['description']))
                    <p class="step-description text-sm text-gray-600">
                        {{ $step['description'] }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
