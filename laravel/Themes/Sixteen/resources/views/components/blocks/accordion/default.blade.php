@props(['data' => []])

@php
    $items = $data['items'] ?? [];
    $accordionId = 'accordion-faq-' . uniqid();
@endphp

<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 px-0 px-sm-3">
        <div class="cmp-accordion faq">
            <div class="accordion" id="{{ $accordionId }}" x-data="{ activeIndex: null }">
                @foreach($items as $index => $item)
                    <div class="accordion-item" 
                         data-faq-item 
                         data-faq-text="{{ Str::lower(strip_tags(($item['question'] ?? '').' '.preg_replace('~<[^>]+>~', ' ', (string) ($item['answer'] ?? '')))) }}">
                        <div class="accordion-header" id="headingfaq-{{ $index + 1 }}">
                            <button class="accordion-button collapsed title-small-semi-bold py-3"
                                    type="button"
                                    @click="activeIndex === {{ $index }} ? activeIndex = null : activeIndex = {{ $index }}"
                                    :aria-expanded="activeIndex === {{ $index }}"
                                    :class="{ 'collapsed': activeIndex !== {{ $index }} }"
                                    aria-controls="collapsefaq-{{ $index + 1 }}">
                                <div class="button-wrapper">
                                    {{ $item['question'] }}
                                    <div class="icon-wrapper">
                                        <svg class="icon icon-xs me-1 icon-primary"
                                             :class="{ 'rotate-180': activeIndex === {{ $index }} }">
                                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div id="collapsefaq-{{ $index + 1 }}"
                             class="accordion-collapse collapse p-0"
                             role="region"
                             aria-labelledby="headingfaq-{{ $index + 1 }}"
                             x-show="activeIndex === {{ $index }}"
                             x-cloak
                             style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;"
                             :style="activeIndex === {{ $index }} ? 'max-height: 1000px; transition: max-height 0.3s ease-in;' : 'max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;'"
                             @click.outside="if (activeIndex === {{ $index }}) activeIndex = null">
                            <div class="accordion-body">
                                {!! $item['answer'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
