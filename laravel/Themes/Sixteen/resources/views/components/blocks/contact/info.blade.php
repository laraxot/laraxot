{{--
    Contact Info Block
    Reference: design-comuni-pagine-statiche/src/pages/sito/homepage.hbs cmp-contacts
--}}
@props([
    'title'       => 'Hai riscontrato dei problemi?',
    'description' => 'Aiutaci a migliorare questo sito.',
    'cta_label'   => 'Segnala un problema',
    'cta_url'     => '#',
])

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center p-contacts">
            <div class="col-12 col-lg-6">
                <div class="cmp-contacts">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ $title }}</h2>
                            @if($description)
                            <p class="subtitle-small">{{ $description }}</p>
                            @endif
                            @if($cta_label)
                            <ul class="contact-list mt-4">
                                <li>
                                    <a class="list-item" href="{{ $cta_url }}">
                                        <svg class="icon" aria-hidden="true">
                                            <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-mail') }}"></use>
                                        </svg>
                                        <span>{{ $cta_label }}</span>
                                    </a>
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
