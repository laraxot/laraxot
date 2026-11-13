@props(['data' => []])
{{--
    Intestazione del dettaglio servizio: titolo, stato (chip), sommario, pulsante principale e due tendine (condividi, azioni).
    Dati: title, status (label, element), subtitle, primary_action (label, element), actions[] (id, aria_label, icon, label,
    items[] con icon, label, url). Le tendine usano il comportamento del tema (stesso markup di event-detail).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $sprite = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-heading pb-3 pb-lg-4">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="title-xxxlarge" data-element="service-title">{{ $d('title') }}</h1>
                        <ul class="d-flex flex-wrap gap-1 my-3">
                            <li>
                                <div class="chip chip-simple text-button" data-element="{{ $d('status.element') }}">
                                    <span class="chip-label">{{ $d('status.label') }}</span>
                                </div>
                            </li>
                        </ul>
                        <p class="subtitle-small mb-3">{{ $d('subtitle') }}</p>
                        <button type="button" data-element="{{ $d('primary_action.element') }}" class="btn btn-primary fw-bold">
                            <span class="">{{ $d('primary_action.label') }}</span>
                        </button>
                    </div>
                    <div class="col-lg-3 offset-lg-1 mt-5 mt-lg-0">
                        @foreach ($d('actions', []) as $action)
                            <div class="dropdown">
                                <button aria-label="{{ $action['aria_label'] ?? '' }}" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" type="button" id="{{ $action['id'] ?? '' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($action['icon'] ?? '') }}"></use></svg>
                                    <small>{{ $action['label'] ?? '' }}</small>
                                </button>
                                <div class="dropdown-menu shadow-lg" aria-labelledby="{{ $action['id'] ?? '' }}">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list" role="menu">
                                            @foreach ($action['items'] ?? [] as $entry)
                                                <li role="none">
                                                    <a class="list-item" href="{{ $entry['url'] ?? '#' }}" role="menuitem">
                                                        <svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($entry['icon'] ?? '') }}"></use></svg>
                                                        <span>{{ $entry['label'] ?? '' }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr class="d-none d-lg-block mt-2">
    </div>
</div>
