@props([
    'contacts' => [],
    'contactsId' => 'info-contacts',
    'sprite' => '',
])

@if (!empty($contacts))
    <section id="{{ $contactsId }}">
        <div class="bg-grey-card shadow-contacts">
            <div class="container">
                <div class="row d-flex justify-content-center p-contacts">
                    <div class="col-12 col-lg-5">
                        <div class="cmp-contacts">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h2 class="title-medium-2-semi-bold">{{ $contacts['title'] }}</h2>
                                    <ul class="contact-list p-0">
                                        @foreach ($contacts['items'] as $contact)
                                            @php
                                                $icon = $contact['icon'] ?? 'it-help-circle';
                                                $dataElement = $contact['data_element'] ?? null;
                                            @endphp
                                            <li>
                                                <a class="list-item" href="{{ $contact['url'] }}"@if ($dataElement) data-element="{{ $dataElement }}"@endif>
                                                    <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                        <use href="{{ $sprite }}#{{ $icon }}"></use>
                                                    </svg>
                                                    <span>{{ $contact['label'] }}</span>
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
    </section>
@endif
