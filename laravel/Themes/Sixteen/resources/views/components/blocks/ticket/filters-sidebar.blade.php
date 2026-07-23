@props([
    'filters' => [],
    'statusFilters' => [],
    'selectedTypes' => [],
    'selectedStatuses' => [],
    'context' => 'desktop',
])

@if (!empty($filters['items']))
    <fieldset class="mb-0">
        <legend class="h6 text-uppercase category-list__title">{{ $filters['title'] }}</legend>
        <div class="categoy-list pb-3">
            <ul>
                @foreach ($filters['items'] as $item)
                    @php
                        $value = (string) ($item['value'] ?? '');
                        $inputId = (string) ($item['id'] ?? 'filter');
                        if ($context === 'mobile') {
                            $inputId .= '-mobile';
                        }
                        $isChecked = in_array($value, $selectedTypes, true);
                        $displayLabel = (string) ($item['display_label'] ?? $item['label'] ?? '');
                        $iconUrl = (string) ($item['iconUrl'] ?? $item['icon_url'] ?? '');
                    @endphp
                    <li>
                        <div class="form-check">
                            <div class="checkbox-body border-light py-1">
                                <input
                                    id="{{ $inputId }}"
                                    type="checkbox"
                                    name="category"
                                    value="{{ $value }}"
                                    data-filter-type="{{ $value }}"
                                    data-count="{{ (int) ($item['count'] ?? 0) }}"
                                    x-on:change="$dispatch('filter-type-changed', { type: '{{ $value }}', checked: $el.checked })"
                                    @checked($isChecked)
                                >
                                <label
                                    for="{{ $inputId }}"
                                    class="subtitle-small_semi-bold mb-0 category-list__list filter-type-label d-inline-flex align-items-center flex-nowrap gap-2"
                                >
                                    @if ($iconUrl !== '')
                                        <img
                                            src="{{ $iconUrl }}"
                                            alt=""
                                            class="filter-type-icon flex-shrink-0"
                                            width="20"
                                            height="20"
                                            loading="lazy"
                                        >
                                    @endif
                                    <span class="filter-type-label__text">
                                    @if (preg_match('/\(\d+\)\s*$/', $displayLabel) === 1)
                                        {{ $displayLabel }}
                                    @else
                                        {{ $displayLabel }}
                                        <span class="count text-muted ms-1">({{ (int) ($item['count'] ?? 0) }})</span>
                                    @endif
                                    </span>
                                </label>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </fieldset>
@endif

@if (!empty($statusFilters['items']))
    <fieldset class="mb-0 pt-3 mt-2 border-top border-light filter-status-fieldset">
        <legend class="h6 text-uppercase category-list__title">{{ $statusFilters['title'] }}</legend>
        <div class="categoy-list pb-4">
            <ul>
                @foreach ($statusFilters['items'] as $item)
                    @php
                        $value = (string) ($item['value'] ?? '');
                        $inputId = (string) ($item['id'] ?? 'filter-status');
                        if ($context === 'mobile') {
                            $inputId .= '-mobile';
                        }
                        $isChecked = in_array($value, $selectedStatuses, true);
                        $displayLabel = (string) ($item['display_label'] ?? $item['label'] ?? '');
                        $statusColor = (string) ($item['color'] ?? '#607d8b');
                    @endphp
                    <li>
                        <div class="form-check">
                            <div class="checkbox-body border-light py-1">
                                <input
                                    id="{{ $inputId }}"
                                    type="checkbox"
                                    name="status"
                                    value="{{ $value }}"
                                    data-filter-status="{{ $value }}"
                                    data-count="{{ (int) ($item['count'] ?? 0) }}"
                                    x-on:change="$dispatch('filter-status-changed', { status: '{{ $value }}', checked: $el.checked })"
                                    @checked($isChecked)
                                >
                                <label
                                    for="{{ $inputId }}"
                                    class="subtitle-small_semi-bold mb-0 category-list__list filter-type-label filter-status-label d-inline-flex align-items-center flex-nowrap gap-2"
                                >
                                    <span
                                        class="filter-status-color flex-shrink-0"
                                        style="background-color: {{ $statusColor }}"
                                        aria-hidden="true"
                                    ></span>
                                    <span class="filter-type-label__text">
                                    @if (preg_match('/\(\d+\)\s*$/', $displayLabel) === 1)
                                        {{ $displayLabel }}
                                    @else
                                        {{ $displayLabel }}
                                        <span class="count text-muted ms-1">({{ (int) ($item['count'] ?? 0) }})</span>
                                    @endif
                                    </span>
                                </label>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </fieldset>
@endif
