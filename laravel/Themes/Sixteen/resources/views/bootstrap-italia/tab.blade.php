{{-- Bootstrap Italia Tab Component --}}

@php
$tabId = $id ?? 'tab-' . uniqid();
@endphp

<div class="tab-container tab-italia">
    <ul class="nav nav-tabs" id="{{ $tabId }}" role="tablist">
        @foreach($tabs ?? [] as $key => $tab)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ ($activeTab ?? array_key_first($tabs ?? [])) === $key ? 'active' : '' }}"
                    id="{{ $tabId }}-{{ $key }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tabId }}-{{ $key }}"
                    type="button"
                    role="tab"
                    aria-controls="{{ $tabId }}-{{ $key }}"
                    aria-selected="{{ ($activeTab ?? array_key_first($tabs ?? [])) === $key ? 'true' : 'false' }}">
                @if(isset($tab['icon']))
                <svg class="icon tab-icon" aria-hidden="true">
                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#{{ $tab['icon'] }}"></use>
                </svg>
                @endif
                {{ $tab['label'] }}
            </button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="{{ $tabId }}-content">
        @foreach($tabs ?? [] as $key => $tab)
        <div class="tab-pane fade {{ ($activeTab ?? array_key_first($tabs ?? [])) === $key ? 'show active' : '' }}"
             id="{{ $tabId }}-{{ $key }}"
             role="tabpanel"
             aria-labelledby="{{ $tabId }}-{{ $key }}-tab">
            {{ $tab['content'] }}
        </div>
        @endforeach
    </div>
</div>
