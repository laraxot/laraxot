{{--
    Riquadro contatti `cmp-contacts` (JSON: contacts.main e contacts.problems con title e items[icon, label, url, element]).
    DCP-289: i gruppi arrivano da ResolveContactGroupsAction. Una voce con `"ente": "toll_free"` prende numero e link da config('ente'), una voce
    senza URL (o con `#`) o senza il suo dato sparisce, e senza gruppi non si stampa nemmeno il riquadro. Le repliche /tests/ tengono le voci `#`.
--}}
@php
    $contactGroups ??= app(\Themes\Sixteen\Actions\Contacts\ResolveContactGroupsAction::class)->execute(
        (array) $d('contacts', []),
        app(\Modules\Tenant\Actions\Ente\GetEnteDataAction::class)->execute(),
        request()->segment(2) !== 'tests',
    );
@endphp
@if ($contactGroups !== [])
<div class="cmp-contacts">
    <div class="card w-100">
        <div class="card-body">
            @foreach ($contactGroups as $group)
                <h2 @class(['title-medium-2-semi-bold', 'mt-4' => ! $loop->first])>{{ $group['title'] }}</h2>
                <ul class="contact-list p-0">
                    @foreach ($group['items'] as $contact)
                        <li>
                            <a class="list-item" href="{{ $contact['url'] ?? '#' }}"@if (filled($contact['element'] ?? null)) data-element="{{ $contact['element'] }}"@endif>
                                <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon($contact['icon'] ?? 'it-help-circle') }}"></use></svg>
                                <span>{{ $contact['label'] ?? '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</div>
@endif
