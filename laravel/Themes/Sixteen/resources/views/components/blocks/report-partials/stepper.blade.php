{{-- Barra dei passi (JSON: steps.items[label, state = confirmed|active|''], steps.index, steps.confirmed_label, steps.active_label). --}}
<div class="steppers">
    <div class="steppers-header">
        <ul>
            @foreach ($d('steps.items', []) as $step)
                <li class="{{ $step['state'] ?? '' }}"> {{ $step['label'] ?? '' }}
                    @if (($step['state'] ?? '') === 'confirmed')<svg class="icon steppers-success" aria-hidden="true"><use href="{{ $icon('it-check') }}"></use></svg><span class="visually-hidden">{{ $d('steps.confirmed_label') }}</span>@endif
                    @if (($step['state'] ?? '') === 'active')<span class="visually-hidden">{{ $d('steps.active_label') }}</span>@endif
                </li>
            @endforeach
        </ul>
        <span class="steppers-index" aria-hidden="true">{{ $d('steps.index') }}</span>
    </div>
</div>
