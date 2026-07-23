@props([
    'data' => [],
    'contacts' => [],
    'contact_title' => null,
    'sprite' => '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg',
])

@php
    $ns = 'fixcity::ticket';

    $phoneNumber = (string) ($data['phone'] ?? '05 0505');

    $translate = static function (mixed $value, string $fallback = '') use ($phoneNumber): string {
        if (is_array($value)) {
            $value = $value[app()->getLocale()] ?? null;
        }
        if ($value === null || $value === '') {
            return str_replace(':phone', $phoneNumber, $fallback);
        }
        $text = (string) $value;
        $resolved = str_contains($text, '::') ? __($text) : $text;

        return str_replace(':phone', $phoneNumber, $resolved);
    };

    $items = is_array($contacts) ? $contacts : [];
    if ($items === [] && is_array($data['contacts'] ?? null)) {
        $items = $data['contacts'];
    }
    if ($items === []) {
        $items = [
            ['label' => $ns . '.contacts.faq.link.label', 'url' => '#', 'icon' => 'it-help-circle'],
            ['label' => $ns . '.contacts.assistenza.link.label', 'url' => '#', 'icon' => 'it-mail'],
            ['label' => $ns . '.contacts.phone.link.label', 'url' => 'tel:050505', 'icon' => 'it-phone'],
            ['label' => $ns . '.contacts.appointment.link.label', 'url' => '#', 'icon' => 'it-calendar'],
        ];
    }

    $title = $translate($contact_title ?? $data['contact_title'] ?? null, __($ns . '.contacts.block_title.label'));
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center p-contacts">
            <div class="col-12 col-lg-5">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ $title }}</h2>
                            <ul class="contact-list p-0">
                                @foreach ($items as $item)
                                    @php
                                        $label = $translate($item['label'] ?? '', '');
                                        $icon = (string) ($item['icon'] ?? 'it-help-circle');
                                        $url = (string) ($item['url'] ?? '#');
                                    @endphp
                                    <li>
                                        <a class="list-item" href="{{ $url }}" @if($icon === 'it-mail') data-element="contacts" @elseif($icon === 'it-calendar') data-element="appointment-booking" @endif>
                                            <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                <use href="{{ $sprite }}#{{ $icon }}"></use>
                                            </svg><span>{{ $label }}</span>
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
