<x-filament-panels::page>
    <div class="space-y-4">
        <div class="text-sm text-gray-600 dark:text-gray-300">
            @if($this->intervention)
                <div>
                    <strong>{{ __('interventions.intervention') }}:</strong>
                    #{{ $this->intervention->id }}
                </div>
            @endif
        </div>

        {{ $this->form }}
    </div>
</x-filament-panels::page>

