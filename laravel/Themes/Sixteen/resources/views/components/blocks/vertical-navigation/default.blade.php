@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $items = $data['items'] ?? [];
    $sprite = $data['sprite'] ?? '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

@if ($title !== '' && $items !== [])
    <section class="vertical-navigation-block" aria-labelledby="vertical-navigation-title">
        <div class="bg-grey-card shadow-contacts">
            <div class="container">
                <div class="row d-flex justify-content-center p-contacts">
                    <div class="col-12 col-lg-5">
                        <div class="cmp-contacts" aria-labelledby="vertical-navigation-title">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h2 class="title-medium-2-semi-bold" id="vertical-navigation-title">{{ $title }}</h2>
                                    <ul class="contact-list p-0 flex flex-col gap-1">
                                        @foreach ($items as $contact)
                                            @php
                                                $icon = $contact['icon'] ?? 'it-help-circle';
                                                $label = $contact['label'] ?? '';
                                                if (str_contains((string) $label, '::')) {
                                                    $label = __((string) $label);
                                                }
                                            @endphp
                                            <li>
                                                <a class="list-item flex items-center gap-2 py-2" href="{{ $contact['url'] ?? '#' }}">
                                                    <svg class="icon icon-primary icon-sm shrink-0" aria-hidden="true">
                                                        <use href="{{ $sprite }}#{{ $icon }}"></use>
                                                    </svg>
                                                    <span>{{ $label }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
