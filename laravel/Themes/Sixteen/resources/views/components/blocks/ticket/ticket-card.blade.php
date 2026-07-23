@props([
    'item' => null,
    'loop' => null,
    'ns' => 'fixcity::ticket',
    'sprite' => '',
])

@php
    $itemLocation = is_array($item->location ?? null) ? $item->location : [];
    $itemAddress = $itemLocation['address'] ?? $itemLocation['display_name'] ?? '';
    $itemTypeLabel = (string) ($item->type_label ?? '');
    $collapseId = 'collapse' . ($loop->iteration ?? 1);
    $demoImages = (int) ($item->demo_images ?? 0);
    $imageSrc = '/themes/Sixteen/design-comuni/assets/images/image-disservizio.png';
@endphp

<div class="cmp-card mb-4 mb-lg-30">
    <div class="card has-bkg-grey shadow-sm">
        <div class="card-body p-0">
            <div class="cmp-info-button-card">
                <div class="card p-3 p-lg-4">
                    <div class="card-body p-0">
                        <h3 class="medium-title mb-0">{{ $item->name ?? '' }}</h3>
                        @if($itemTypeLabel)
                            <p class="card-info">
                                {{ __($ns . '.card.type.label') }}<br><span>{{ $itemTypeLabel }}</span>
                            </p>
                        @endif

                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button
                                    class="collapsed accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $collapseId }}"
                                >
                                    <span class="d-flex align-items-center">
                                        {{ __($ns . '.card.expand.button.label') }}
                                        <svg class="icon icon-primary icon-sm">
                                            <use href="{{ $sprite }}#it-expand"></use>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse @if ($loop->first ?? false) pb-0 @endif" role="region">
                                <div class="accordion-body p-0">
                                    <div class="cmp-info-summary bg-white border-0">
                                        <div class="card">
                                            <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-end">
                                                <a href="#" class="d-none text-decoration-none" data-element="ticket-edit">
                                                    <span class="text-button-sm-semi t-primary">{{ __($ns . '.card.edit.link.label') }}</span>
                                                </a>
                                            </div>
                                            <div class="card-body p-0">
                                                @if ($itemAddress)
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">{{ __($ns . '.card.address.label') }}</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $itemAddress }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (! empty($item->content ?? null))
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">{{ __($ns . '.card.detail.label') }}</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item->content }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($demoImages > 0)
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">{{ __($ns . '.card.photos.label') }}</div>
                                                        <div class="border-light border-0">
                                                            <div class="d-lg-flex gap-2 mt-3">
                                                                @for ($i = 0; $i < $demoImages; $i++)
                                                                    <div>
                                                                        <img
                                                                            src="{{ $imageSrc }}"
                                                                            alt="{{ __($ns . '.modal.detail.image.alt.label') }}"
                                                                            class="img-fluid w-100{{ $i < $demoImages - 1 ? ' mb-3 mb-lg-0' : '' }}"
                                                                            loading="lazy"
                                                                        >
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer p-0 d-none"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
