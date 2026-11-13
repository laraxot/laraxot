{{-- Bootstrap Italia Progress Indicators Component --}}

@if($type === 'spinner')
<div class="progress-spinner {{ ($active ?? false) ? 'progress-spinner-active' : '' }} {{ 'size-' . ($size ?? 'md') }}"
     role="status"
     aria-live="polite">
    <span class="visually-hidden">{{ __('pub_theme::bootstrap_italia.progress_indicators.loading.message') }}</span>
</div>
@else
@php
$percentage = round(($value ?? 0) * 100);
@endphp
<div class="progress" role="progressbar"
     aria-valuenow="{{ $percentage }}"
     aria-valuemin="0"
     aria-valuemax="100">
    <div class="progress-bar progress-bar-italia"
         style="width: {{ $percentage }}%">
        @if($showLabel ?? false)
        <span class="progress-label">{{ $percentage }}%</span>
        @endif
    </div>
</div>
@endif
