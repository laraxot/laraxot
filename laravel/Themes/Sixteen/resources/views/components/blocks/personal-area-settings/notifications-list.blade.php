{{--
    Vista del componente Livewire PersonalAreaNotificationsWidget (DCP-111). Una sola radice (requisito di Livewire).
    Dati: $notifications = BuildPersonalAreaNotificationsDataAction (unread_count, has_more, items[id, ticket_code, ticket_label, status_label,
    occurred_at, occurred_iso, is_read, track_url]); $statusMessage = esito dell'ultima azione, in un'area live sempre presente.
--}}
@php
    $t = static fn (string $key, array $replace = []): string => (string) __('fixcity::personal_area_settings.notifications.'.$key, $replace);
    $items = $notifications['items'];
@endphp
<div class="it-page-sections-container">
    <section class="it-page-section pt-0" aria-labelledby="personal-area-notifications-title">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="title-xxlarge mb-1" id="personal-area-notifications-title">{{ $t('list_title') }}</h2>
                <p class="text-paragraph-small mb-0">
                    {{ trans_choice('fixcity::personal_area_settings.notifications.count', count($items), ['count' => count($items)]) }}
                    &middot;
                    {{ trans_choice('fixcity::personal_area_settings.notifications.unread', $notifications['unread_count'], ['count' => $notifications['unread_count']]) }}
                </p>
            </div>
            @if ($notifications['unread_count'] > 0)
                <button type="button" class="btn btn-outline-primary" wire:click="markAllAsRead" wire:loading.attr="disabled">{{ $t('mark_all_read') }}</button>
            @endif
        </div>
        {{-- Area live sempre nel DOM, anche vuota: un'area inserita insieme al testo spesso non viene annunciata. --}}
        <p class="text-success fw-semibold mb-3" role="status" aria-live="polite" aria-atomic="true">{{ $statusMessage }}</p>

        @if ($items === [])
            <div class="cmp-card-latest-messages mb-3">
                <div class="card shadow-sm px-4 pt-4 pb-4">
                    <h3 class="title-small-semi-bold m-0 mb-2">{{ $t('empty_title') }}</h3>
                    <p class="text-paragraph">{{ $t('empty_text') }}</p>
                    <div><a class="btn btn-primary" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaPratiche() }}">{{ $t('my_reports') }}</a></div>
                </div>
            </div>
        @else
            <ul class="list-unstyled mb-0">
                @foreach ($items as $item)
                    <li wire:key="notification-{{ $item['id'] }}">
                        <div class="cmp-card-latest-messages mb-3">
                            <div class="card shadow-sm px-4 pt-4 pb-4">
                                <div class="card-header border-0 p-0 m-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <time class="date-xsmall" datetime="{{ $item['occurred_iso'] }}">{{ $item['occurred_at'] }}</time>
                                    @unless ($item['is_read'])
                                        <span class="chip chip-simple"><span class="chip-label">{{ $t('new_badge') }}</span></span>
                                    @endunless
                                </div>
                                <div class="card-body p-0 my-2">
                                    <h3 @class(['title-small-semi-bold m-0', 'fw-bold' => ! $item['is_read']])>{{ $t('item_text', ['code' => $item['ticket_label'], 'status' => $item['status_label']]) }}</h3>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <a class="btn btn-primary" href="{{ $item['track_url'] }}" aria-label="{{ $t('open_report_aria', ['code' => $item['ticket_label']]) }}">{{ $t('open_report') }}</a>
                                    @unless ($item['is_read'])
                                        <button type="button" class="btn btn-outline-primary" wire:click="markAsRead('{{ $item['id'] }}')" wire:loading.attr="disabled" aria-label="{{ $t('mark_read_aria', ['code' => $item['ticket_label']]) }}">{{ $t('mark_read') }}</button>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            @if ($notifications['has_more'])
                <p class="text-paragraph-small">{{ $t('has_more', ['count' => count($items)]) }}</p>
            @endif
        @endif
    </section>
</div>
