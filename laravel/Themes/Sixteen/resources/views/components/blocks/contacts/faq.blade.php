@php
    $contactTitle = $contact_title ?? __('fixcity::segnalazione.contacts.title.label');
    $contacts = is_array($contacts ?? null) ? $contacts : [];
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $contactIcons = ['it-help-circle', 'it-mail', 'it-hearing', 'it-calendar'];
@endphp

<div class="col-12 col-lg-6 offset-lg-3 p-contacts">
    <div class="cmp-contacts">
        <div class="card w-100">
            <div class="card-body">
                <h2 class="title-medium-2-semi-bold">{{ $contactTitle }}</h2>
                <ul class="contact-list p-0">
                    @foreach($contacts as $index => $contact)
                        <li>
                            <a class="list-item" href="{{ $contact['url'] ?? '#' }}" @if(!empty($contact['data_element'])) data-element="{{ $contact['data_element'] }}" @endif>
                                <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                    <use href="{{ $sprite }}#{{ $contact['icon'] ?? ($contactIcons[$index] ?? 'it-help-circle') }}"></use>
                                </svg>
                                <span>{{ $contact['label'] ?? '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
