{{--
    Contacts Block
    Reference: design-comuni-pagine-statiche/sito/homepage.html .bg-grey-card
--}}
@php
    $data = $data ?? [];
    $contact_links = isset($contact_links) && is_array($contact_links) ? $contact_links : ($data['contact_links'] ?? []);
    $report_links  = isset($report_links) && is_array($report_links) ? $report_links : ($data['report_links'] ?? []);
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center p-contacts">
            <div class="col-12 col-lg-6">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">Contatta il comune</h2>
                            <ul class="contact-list p-0">
                                @foreach($contact_links as $link)
                                <li>
                                    <a class="list-item" href="{{ $link['url'] ?? '#' }}" @if(!empty($link['data_element'])) data-element="{{ $link['data_element'] }}" @endif>
                                        <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                            <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#' . ($link['icon'] ?? 'it-mail')) }}"></use>
                                        </svg>
                                        <span>{{ $link['label'] }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @if(!empty($report_links))
                            <h2 class="title-medium-2-semi-bold mt-4">Problemi in città</h2>
                            <ul class="contact-list p-0">
                                @foreach($report_links as $link)
                                <li>
                                    <a class="list-item" href="{{ $link['url'] ?? '#' }}">
                                        <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                            <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#' . ($link['icon'] ?? 'it-map-marker-circle')) }}"></use>
                                        </svg>
                                        <span>{{ $link['label'] }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
