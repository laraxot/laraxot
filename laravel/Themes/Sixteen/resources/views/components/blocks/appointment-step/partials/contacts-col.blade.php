{{--
    Contatti con `p-contacts` sulla colonna (passi 5 e 6 del riferimento; il partial condiviso dc-contacts lo mette sulla riga). Stessi dati
    JSON di dc-contacts: contacts.main{title, items[icon, label, url]}.
--}}
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-lg-6 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ $d('contacts.main.title') }}</h2>
                            <ul class="contact-list p-0">
                                @foreach ($d('contacts.main.items', []) as $contact)
                                    <li>
                                        <a class="list-item" href="{{ $contact['url'] ?? '#' }}">
                                            <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon($contact['icon'] ?? 'it-help-circle') }}"></use></svg>
                                            <span>{{ $contact['label'] ?? '' }}</span>
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
