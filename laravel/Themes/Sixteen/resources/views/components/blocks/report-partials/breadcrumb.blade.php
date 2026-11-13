{{-- Briciole di pane (JSON: breadcrumb.items[label, url], breadcrumb.current). Usa $d dal blocco che include. --}}
<div class="cmp-breadcrumbs" role="navigation">
    {{-- Caller: ticket-list/detail + report-* via @include report-partials.breadcrumb; SSoT aria A-14. User: audit UI/UX FO DesignComuni. --}}
    <nav class="breadcrumb-container" aria-label="{{ __('fixcity::global.breadcrumb') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
        @foreach ($d('breadcrumb.items', []) as $crumb)
            <li class="breadcrumb-item">
                @if (filled($crumb['url'] ?? null) && ($crumb['url'] ?? '') !== '#')
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] ?? '' }}</a>
                @else
                    <span>{{ $crumb['label'] ?? '' }}</span>
                @endif
                <span class="separator">/</span>
            </li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
    </ol></nav>
</div>
