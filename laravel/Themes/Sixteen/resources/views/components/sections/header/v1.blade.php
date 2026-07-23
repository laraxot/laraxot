@php
    $authUser = auth()->user();
    $headerProfile = $authUser?->profile;
    $headerUserDisplayName = trim((string) ($headerProfile->user_name ?? ''));

    if ($headerUserDisplayName === '') {
        $headerUserDisplayName = trim((string) ($headerProfile->full_name ?? ''));
    }
    if ($headerUserDisplayName === '') {
        $headerUserDisplayName = trim((string) ($authUser->user_name ?? ''));
    }
    if ($headerUserDisplayName === '') {
        $headerUserDisplayName = trim((string) ($authUser->full_name ?? ''));
    }
    if ($headerUserDisplayName === '') {
        $headerUserDisplayName = trim((string) (($authUser->first_name ?? '').' '.($authUser->last_name ?? '')));
    }
    if ($headerUserDisplayName === '') {
        $headerUserDisplayName = (string) ($authUser->name ?? $authUser->email ?? 'Account');
    }

    $headerAvatarUrl = null;
    // FO header parity: media locale → Gravatar (email) → iniziale
    if (\is_object($headerProfile) && method_exists($headerProfile, 'getFirstMediaUrl')) {
        $mediaAvatar = (string) $headerProfile->getFirstMediaUrl('avatar');
        if ($mediaAvatar !== '') {
            $headerAvatarUrl = $mediaAvatar;
        }
    }
    if ($headerAvatarUrl === null && filled($headerProfile->avatar_url ?? null) && \is_string($headerProfile->avatar_url)) {
        $headerAvatarUrl = $headerProfile->avatar_url;
    } elseif (isset($authUser->profile_photo_url) && is_string($authUser->profile_photo_url) && $authUser->profile_photo_url !== '') {
        $headerAvatarUrl = $authUser->profile_photo_url;
    } elseif (! empty($authUser->profile_photo_path)) {
        $profilePhotoPath = $authUser->profile_photo_path;
        if (\Illuminate\Support\Str::startsWith($profilePhotoPath, ['http://', 'https://'])) {
            $headerAvatarUrl = $profilePhotoPath;
        } elseif (\Illuminate\Support\Str::startsWith($profilePhotoPath, '/')) {
            $headerAvatarUrl = url($profilePhotoPath);
        } else {
            $headerAvatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($profilePhotoPath);
        }
    }

    if ($headerAvatarUrl === null && $authUser !== null) {
        $headerAvatarEmail = trim((string) (($headerProfile->email ?? null) ?: ($authUser->email ?? '')));
        if ($headerAvatarEmail !== '') {
            $headerAvatarUrl = 'https://www.gravatar.com/avatar/'.md5(mb_strtolower($headerAvatarEmail)).'?s=40&d=mp';
        }
    }

    $headerUserInitial = strtoupper((string) \Illuminate\Support\Str::substr($headerUserDisplayName, 0, 1));

    $headerUnreadNotificationsCount = 0;
    if ($authUser !== null && app(\Modules\User\Actions\Notification\IsNotificationSchemaReadableAction::class)->execute()) {
        try {
            $headerUnreadNotificationsCount = $authUser->unreadNotifications()->count();
        } catch (\Throwable) {
            $headerUnreadNotificationsCount = 0;
        }
    }

    $testsPath = (string) request()->path();
    /** Story 7-3: chrome slim come kit statico Design Comuni per compare-html.sh (path tests/segnalazione-area-personale) */
    $headerHtmlParityPersonalArea = str_contains($testsPath, 'tests/segnalazione-area-personale');
    $headerRegionLabel = __('pub_theme::header.slim.region.name.label');

    // Story 8-107: nav items dinamici da header.json (no hardcoded)
    $headerNavConfig = [];
    $headerNavJsonPath = app(\Modules\Tenant\Actions\Config\GetTenantFilePathAction::class)->execute('database/content/sections/header.json');
    if (is_string($headerNavJsonPath) && file_exists($headerNavJsonPath)) {
        $headerNavConfig = \Illuminate\Support\Facades\File::json($headerNavJsonPath);
    }
    $headerNavAllItems  = $headerNavConfig['sections']['primary_nav']['items'] ?? [];
    $headerFolioUrl = static function (string $url): string {
        if ($url === '' || $url === '#') {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return $url;
        }

        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', ['it' => []]));
        $segments = array_values(array_filter(explode('/', trim($path, '/')), static fn (string $segment): bool => $segment !== ''));
        if ($segments !== [] && in_array($segments[0], $supportedLocales, true)) {
            array_shift($segments);
        }

        $container = $segments[0] ?? '';
        if ($container === '') {
            return route('home');
        }

        $container = match ($container) {
            'amministrazione' => 'administration',
            'novita' => 'news',
            'servizi', 'lista-categorie' => 'services',
            'eventi', 'vivere-il-comune' => 'events',
            'argomenti' => 'topics',
            'iscrizioni' => 'registrations',
            'estate-in-citta' => 'summer-in-the-city',
            'polizia-locale' => 'local-police',
            default => $container,
        };

        return route('container0.index', ['container0' => $container]);
    };

    $headerNavTopicsUrl = $headerFolioUrl(
        (string) ($headerNavConfig['sections']['primary_nav']['topics_url'] ?? '/argomenti')
    );
    $headerNavItems     = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'primary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    $headerNavSecondary = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'secondary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    usort($headerNavItems,     fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    usort($headerNavSecondary, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

    $headerNavItemIsActive = static function (array $item): bool {
        $patterns = $item['active_patterns'] ?? [];
        if (\is_array($patterns) && $patterns !== []) {
            foreach ($patterns as $p) {
                if (! \is_string($p) || $p === '') {
                    continue;
                }
                $normalized = ltrim($p, '/');
                if ($normalized !== '' && request()->is($normalized)) {
                    return true;
                }
            }

            return false;
        }
        $u = (string) ($item['url'] ?? '');
        $path = $u !== '' ? ltrim((string) parse_url($u, PHP_URL_PATH), '/') : '';

        return $path !== '' && (request()->is($path) || request()->is($path.'/*'));
    };
@endphp

{{--
    Bootstrap Italia Header — EXACT match of Design Comuni reference
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
    Reference: https://italia.github.io/design-comuni-pagine-statiche/servizi/graduatoria-area-personale.html
    
    Updated for Story 5.0: Header Logged-In State
    - Auth state detection
    - User dropdown for authenticated users
    - Area Personale menu (servizi, pratiche, notifiche, impostazioni, logout)
    
    Updated for Story 8-34: Real section owner fix
    - Section header is the real runtime owner for Design Comuni header chrome
    - Slim dropdowns lingua + utente: data-bs-toggle + app.js (Story 7-54), no Alpine inline (Livewire/Filament)
    - Authenticated user block prioritizes display name over decorative avatar
    - Sfondo slim: token design-comuni (no override hex inline; vedi design-comuni-tokens.css)
--}}
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
    {{-- Slim Header: background from theme tokens --}}
    <div class="it-header-slim-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        {{-- Transparent bg: shows slim dark green (#00402b) underneath, text-white for contrast --}}
                        <a
                            class="d-lg-block navbar-brand"
                            target="_blank"
                            rel="noopener noreferrer"
                            href="#"
                            aria-label="{{ __('pub_theme::header.slim.region.portal_aria.label', ['region' => $headerRegionLabel]) }}"
                            title="{{ __('pub_theme::header.slim.region.portal_title.label', ['region' => $headerRegionLabel]) }}"
                        >{{ $headerRegionLabel }}</a>

                        <div class="it-header-slim-right-zone" role="navigation">
                            @include('pub_theme::components.sections.header.partials.language-switcher')
                            @guest
                                @include('pub_theme::components.sections.header.partials.personal-area-guest-cta')
                            @else
                                @include('pub_theme::components.sections.header.partials.user-dropdown', [
                                    'avatarUrl' => $headerAvatarUrl,
                                    'displayName' => $headerUserDisplayName,
                                    'unreadNotificationsCount' => $headerUnreadNotificationsCount,
                                    'userInitial' => $headerUserInitial,
                                ])
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="it-nav-wrapper" data-sixteen-mobile-nav>
        <div class="it-header-center-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            <div class="it-brand-wrapper">
                                <a href="/" title="{{ __('pub_theme::header.center.brand.home_link.title.label') }}">
                                    <svg width="82" height="82" class="icon" aria-hidden="true">
                                        <image xlink:href="/themes/Sixteen/design-comuni/assets/images/logo-comune.svg"/>
                                    </svg>
                                    <div class="it-brand-text">
                                        <div class="it-brand-title">{{ __('pub_theme::header.center.brand.title.label') }}</div>
                                        <div class="it-brand-tagline d-none d-md-block">{{ __('pub_theme::header.center.brand.tagline.label') }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="it-right-zone">
                                <div class="it-socials d-none d-lg-flex">
                                    <span>{{ __('pub_theme::header.center.social.follow.label') }}</span>
                                    <ul>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.twitter.label') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.facebook.label') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.youtube.label') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.telegram.label') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.whatsapp.label') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ __('pub_theme::header.social.rss.label') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="it-search-wrapper">
                                    <span class="d-none d-md-block">{{ __('pub_theme::header.center.search.label') }}</span>
                                    <button class="search-link rounded-icon" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="{{ __('pub_theme::header.center.search.toggle_aria.label') }}">
                                        <svg class="icon">
                                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="navbar navbar-expand-lg has-megamenu">
                            <button
                                class="custom-navbar-toggler"
                                type="button"
                                aria-controls="nav4"
                                aria-expanded="false"
                                aria-label="{{ __('pub_theme::header.center.nav.toggle_aria.label') }}"
                                data-bs-target="#nav4"
                                data-bs-toggle="navbarcollapsible"
                                data-sixteen-mobile-nav-toggle
                            >
                                <svg class="icon">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-burger"></use>
                                </svg>
                            </button>
                            <div class="navbar-collapsable" id="nav4">
                                <div class="overlay" style="display: none;"></div>
                                <div class="close-div">
                                    <button class="btn close-menu" type="button">
                                        <span class="visually-hidden">{{ __('pub_theme::header.center.nav.close_aria.label') }}</span>
                                        <svg class="icon">
                                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-close-big"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div class="menu-wrapper">
                                    <a href="/" class="logo-hamburger">
                                        <svg class="icon" aria-hidden="true">
                                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
                                        </svg>
                                        <div class="it-brand-text">
                                            <div class="it-brand-title">{{ __('pub_theme::header.center.nav.hamburger_brand.label') }}</div>
                                        </div>
                                    </a>
                                    @include('pub_theme::components.sections.header.partials.nav-primary', [
                                        'headerNavItems' => $headerNavItems,
                                        'headerNavItemIsActive' => $headerNavItemIsActive
                                    ])
                                    @include('pub_theme::components.sections.header.partials.nav-secondary', [
                                        'headerNavSecondary' => $headerNavSecondary,
                                        'headerNavTopicsUrl' => $headerNavTopicsUrl,
                                        'headerNavItemIsActive' => $headerNavItemIsActive
                                    ])
                                    <div class="it-socials">
                                        <span>{{ __('pub_theme::header.center.social.follow.label') }}</span>
                                        <ul>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.twitter.label') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.facebook.label') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.youtube.label') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.telegram.label') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.whatsapp.label') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    <svg class="icon icon-sm icon-white align-top">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use>
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('pub_theme::header.social.rss.label') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
