@props(['data' => []])
{{--
    Pagina "Impostazioni e privacy" dell'area personale (DCP-111), JSON `area-personale.impostazioni`. Tre sezioni con indice laterale:
    (1) preferenze di notifica = componente Livewire TicketNotificationPreferencesWidget (id campo `email-ticket-updates`, Action
    Get/SetFixcityEmailNotificationPreference); (2) dati dell'account in sola lettura (BuildPersonalAreaSettingsDataAction, solo
    l'utente autenticato); (3) diritti GDPR con i soli canali che esistono davvero (contatti dell'ente nel footer, Garante); (4) eliminazione dell'account (form POST verso Gdpr DeleteAccountAction).
    Nessun testo qui: tutto da fixcity::personal_area_settings.*. Chrome tab = personal-area/heading (stesso di pratiche/seguite/notifiche). Classi Design Comuni (cmp-heading, cmp-navscroll, cmp-info-summary).
--}}
@php
    $t = static fn (string $key, array $replace = []): string => (string) __('fixcity::personal_area_settings.settings.'.$key, $replace);
    $citizen = auth()->user();
    $settings = $citizen instanceof \Modules\Xot\Contracts\UserContract
        ? app(\Modules\Fixcity\Actions\BuildPersonalAreaSettingsDataAction::class)->execute($citizen)
        : ['account_rows' => [], 'email_verified' => false];
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $nav = [
        'suffix' => 'settings',
        'title' => $t('nav_title'),
        'items' => [
            ['anchor' => 'notification-preferences', 'label' => $t('sections.preferences')],
            ['anchor' => 'account-data', 'label' => $t('sections.account')],
            ['anchor' => 'data-rights', 'label' => $t('sections.rights')],
            ['anchor' => 'account-deletion', 'label' => $t('sections.deletion')],
        ],
    ];
@endphp
<div class="container" id="main-container">
    @include('pub_theme::components.blocks.personal-area.heading', [
        'active' => 'settings',
        'title' => $t('title'),
        'subtitle' => $t('subtitle'),
    ])
    <div class="it-page-sections-container">
        <div class="row">
            <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
                @include('pub_theme::components.blocks.personal-area-settings.page-index', ['nav' => $nav])
            </div>
            <div class="col-12 col-lg-8 offset-lg-1">
                <section class="it-page-section mb-5" id="notification-preferences" aria-labelledby="ticket-notification-preferences-title">
                    @livewire(\Modules\Fixcity\Livewire\TicketNotificationPreferencesWidget::class)
                    <p class="text-paragraph-small mt-3 mb-2">{{ $settings['email_verified'] ? $t('email_verified') : $t('email_unverified') }}</p>
                    <a class="btn btn-outline-primary" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaNotifiche() }}">{{ $t('notifications_link') }}</a>
                </section>

                <section class="it-page-section mb-5" id="account-data" aria-labelledby="account-data-title">
                    <h2 class="title-xxlarge mb-3" id="account-data-title">{{ $t('sections.account') }}</h2>
                    <p class="text-paragraph">{{ $t('account.intro') }}</p>
                    <div class="cmp-info-summary bg-white">
                        <div class="card">
                            <div class="card-body p-0">
                                @foreach ($settings['account_rows'] as $row)
                                    <div class="single-line-info border-light">
                                        <div class="text-paragraph-small">{{ $row['label'] }}</div>
                                        <div class="border-light"><p class="data-text">{{ $row['value'] }}</p></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-paragraph-small mt-3">{{ $t('account.note') }}</p>
                </section>

                <section class="it-page-section mb-5" id="data-rights" aria-labelledby="data-rights-title">
                    <h2 class="title-xxlarge mb-3" id="data-rights-title">{{ $t('sections.rights') }}</h2>
                    <p class="text-paragraph">{{ $t('rights.intro') }}</p>
                    <ul class="list-unstyled">
                        @foreach (trans('fixcity::personal_area_settings.settings.rights.items') as $right)
                            <li class="mb-3">
                                <h3 class="title-small-semi-bold mb-1"><span class="me-2">{{ $right['title'] }}</span><span class="chip chip-simple"><span class="chip-label">{{ $t('rights.article', ['article' => $right['article']]) }}</span></span></h3>
                                <p class="text-paragraph-small mb-0">{{ $right['text'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-paragraph-small">{{ $t('rights.limits') }}</p>
                    <h3 class="title-large-semi-bold mt-4 mb-2">{{ $t('rights.how_title') }}</h3>
                    {{-- DCP-289: a chi scrivere viene dall'ente (DPO, poi PEC, poi email); senza, il link al footer solo se il footer elenca recapiti; altrimenti un testo onesto --}}
                    @php
                        $enteData = app(\Modules\Tenant\Actions\Ente\GetEnteDataAction::class)->execute();
                        $rightsContact = $enteData->rightsContact();
                    @endphp
                    @if ($rightsContact !== null)
                        <p class="text-paragraph">{{ $t('rights.how_text') }}</p>
                        <p><a class="btn btn-primary" href="{{ $rightsContact['href'] }}">{{ __('fixcity::ente.rights.write', ['address' => $rightsContact['label']]) }}</a></p>
                    @elseif ($enteData->hasContacts())
                        <p class="text-paragraph">{{ $t('rights.how_text') }}</p>
                        <p><a class="btn btn-primary" href="#footer">{{ $t('rights.how_link') }}</a></p>
                    @else
                        <p class="text-paragraph">{{ __('fixcity::ente.rights.missing') }}</p>
                    @endif
                    <p class="text-paragraph-small">{{ $t('rights.how_today') }}</p>
                    <p class="text-paragraph">{{ $t('rights.complaint_text') }} <a class="t-primary fw-semibold" href="{{ $t('rights.complaint_url') }}" rel="noopener">{{ $t('rights.complaint_link') }}</a></p>
                </section>

                {{-- GDPR art. 17 self-service: POST /{lang}/account/delete (Gdpr DeleteAccountAction, ability deleteOwnAccount). The required
                     checkbox is the confirmation; the Action validates it again (`confirm` = `cancella`) and redirects back with the error. --}}
                <section class="it-page-section mb-5" id="account-deletion" aria-labelledby="account-deletion-title">
                    <h2 class="title-xxlarge mb-3" id="account-deletion-title">{{ $t('sections.deletion') }}</h2>
                    <p class="text-paragraph">{{ $t('deletion.intro') }}</p>
                    <p class="text-paragraph-small" id="account-deletion-keep">{{ $t('deletion.keep') }}</p>
                    <form method="POST" action="{{ url('/'.app()->getLocale().'/account/delete') }}" aria-describedby="account-deletion-keep">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="account-deletion-reason" class="active">{{ $t('deletion.reason_label') }}</label>
                            <textarea class="form-control" id="account-deletion-reason" name="reason" rows="3" maxlength="1000">{{ old('reason') }}</textarea>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="account-deletion-confirm" name="confirm" value="cancella" required
                                @error('confirm') aria-invalid="true" aria-describedby="account-deletion-confirm-error" @enderror>
                            <label class="form-check-label" for="account-deletion-confirm">{{ $t('deletion.confirm_label') }}</label>
                            @error('confirm')
                                <p class="text-danger text-paragraph-small mt-2" id="account-deletion-confirm-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger">{{ $t('deletion.submit') }}</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
