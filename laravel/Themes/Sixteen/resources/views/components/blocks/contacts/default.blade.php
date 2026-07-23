{{--
    Contacts Card Block - Design Comuni Segnalazione Style
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
    Used as a separate content block in JSON (NOT hardcoded in blade)
    
    Philosophy: Blocks are defined in JSON content_blocks, NOT hardcoded in blade templates.
    This keeps blade templates minimal and generic, while content is data-driven.
--}}
@props(['data' => []])

@php
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $heading = (string) ($data['heading'] ?? __('fixcity::segnalazione.contact.heading.label'));
    $items = (array) ($data['items'] ?? []);
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ $heading }}</h2>
                            <ul class="contact-list p-0">
                                @foreach($items as $item)
                                <li>
                                    <a class="list-item" href="{{ $item['url'] ?? '#' }}" @if(!empty($item['data_element'])) data-element="{{ $item['data_element'] }}" @endif>
                                        <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                            <use href="{{ $sprite }}#{{ $item['icon'] ?? 'it-help-circle' }}"></use>
                                        </svg>
                                        <span>{{ $item['label'] ?? '' }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
