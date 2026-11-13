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

    $emptyGravatarSha = 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855';
    if (
        is_string($headerAvatarUrl)
        && $headerAvatarUrl !== ''
        && str_contains($headerAvatarUrl, $emptyGravatarSha)
    ) {
        $headerAvatarUrl = null;
    }

    // Fallback to Gravatar if no avatar found
    if (empty($headerAvatarUrl) && !empty($authUser->email)) {
        $headerAvatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($authUser->email))) . '?s=20&d=mp';
    }

    $headerUserInitial = strtoupper((string) \Illuminate\Support\Str::substr($headerUserDisplayName, 0, 1));
@endphp

<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
    <div class="it-header-slim-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        <a class="d-lg-block navbar-brand" target="_blank" rel="noopener noreferrer" href="{{ url('/'.app()->getLocale()) }}" aria-label="{{ __('pub_theme::components_header.brand.go_home_new_tab.label') }}" title="{{ __('pub_theme::components_header.brand.go_home.title') }}">{{ config('app.name', __('pub_theme::header.center.brand.title.label')) }}</a>

                        <div class="it-header-slim-right-zone" role="navigation">
                            @include('pub_theme::components.sections.header.partials.language-switcher')
                            @guest
                                <a class="btn btn-primary btn-icon btn-full" href="{{ route('login') }}" data-element="personal-area-login">
                                    <span class="rounded-icon" aria-hidden="true">
                                        <svg class="icon icon-primary">
                                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
                                        </svg>
                                    </span>
                                    <span class="d-none d-lg-block">{{ __('pub_theme::ui.personal_area') }}</span>
                                </a>
                            @else
                                @include('pub_theme::components.sections.header.partials.user-dropdown', [
                                    'avatarUrl' => $headerAvatarUrl,
                                    'displayName' => $headerUserDisplayName,
                                    'unreadNotificationsCount' => $authUser->unreadNotificationsCount ?? 0,
                                    'userInitial' => $headerUserInitial,
                                ])
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="it-nav-wrapper">
        <div class="it-header-center-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            <div class="it-brand-wrapper">
                                <a href="{{ url('/'.app()->getLocale()) }}" title="{{ __('pub_theme::components_header.brand.go_home.title') }}">
                                    <svg width="82" height="82" class="icon" aria-hidden="true">
                                        <image xlink:href="{{ asset('themes/Sixteen/design-comuni/assets/images/logo-comune.svg') }}"/>
                                    </svg>
                                    <div class="it-brand-text">
                                        <div class="it-brand-title">{{ config('app.name', __('pub_theme::header.center.brand.title.label')) }}</div>
                                        <div class="it-brand-tagline d-none d-md-block">{{ config('comune.sottotitolo', __('pub_theme::header.center.brand.tagline.label')) }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="it-right-zone">
                                <div class="it-socials d-none d-lg-flex">
                                    <span>{{ __('pub_theme::header.center.social.follow.label') }}</span>
                                    <ul>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use></svg><span class="visually-hidden">Twitter</span></a></li>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use></svg><span class="visually-hidden">Facebook</span></a></li>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use></svg><span class="visually-hidden">YouTube</span></a></li>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use></svg><span class="visually-hidden">Telegram</span></a></li>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use></svg><span class="visually-hidden">Whatsapp</span></a></li>
                                        <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use></svg><span class="visually-hidden">RSS</span></a></li>
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
                            <button class="custom-navbar-toggler" type="button" aria-controls="nav4" aria-expanded="false" aria-label="{{ __('pub_theme::components_common.a11y.toggle_navigation_it.label') }}" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">
                                <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-burger"></use></svg>
                            </button>
                            <div class="navbar-collapsable" id="nav4">
                                <div class="overlay" style="display: none;"></div>
                                <div class="close-div">
                                    <button class="btn close-menu" type="button">
                                        <span class="visually-hidden">{{ __('pub_theme::header.center.nav.close_aria.label') }}</span>
                                        <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-close-big"></use></svg>
                                    </button>
                                </div>
                                <div class="menu-wrapper">
                                    <a href="{{ url('/'.app()->getLocale()) }}" class="logo-hamburger">
                                        <svg class="icon" aria-hidden="true"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use></svg>
                                        <div class="it-brand-text">
                                            <div class="it-brand-title">{{ config('app.name', __('pub_theme::header.center.nav.hamburger_brand.label')) }}</div>
                                        </div>
                                    </a>
                                    <nav aria-label="{{ __('pub_theme::components_common.a11y.primary.label') }}">
                                        <ul class="navbar-nav" data-element="main-navigation">
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/it/amministrazione') }}" data-element="management"><span>{{ __('pub_theme::header.center.nav.amministrazione.label') }}</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/it/novita') }}" data-element="news"><span>{{ __('pub_theme::header.center.nav.novita.label') }}</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/it/servizi') }}" data-element="all-services"><span>{{ __('pub_theme::header.center.nav.servizi.label') }}</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/it/eventi') }}" data-element="live"><span>{{ __('pub_theme::header.center.nav.vivere.label') }}</span></a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="{{ __('pub_theme::components_common.a11y.secondary.label') }}">
                                        <ul class="navbar-nav navbar-secondary">
                                            <li class="nav-item"><a class="nav-link" href="{{ url('/it/iscrizioni') }}"><span>{{ __('pub_theme::header.center.nav.iscrizioni.label') }}</span></a></li>
                                            <li class="nav-item"><a class="nav-link" href="{{ url('/it/estate-in-citta') }}"><span>{{ __('pub_theme::components_header.nav.summer_city.label') }}</span></a></li>
                                            <li class="nav-item"><a class="nav-link" href="{{ url('/it/polizia-locale') }}"><span>{{ __('pub_theme::components_header.nav.local_police.label') }}</span></a></li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/it/argomenti') }}" data-element="all-topics">
                                                    <span>{{ __('pub_theme::header.center.nav.argomenti.label') }} <svg class="icon icon-sm"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-chevron-right"></use></svg></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="it-socials">
                                        <span>{{ __('pub_theme::header.center.social.follow.label') }}</span>
                                        <ul>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use></svg><span class="visually-hidden">Twitter</span></a></li>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use></svg><span class="visually-hidden">Facebook</span></a></li>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use></svg><span class="visually-hidden">YouTube</span></a></li>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use></svg><span class="visually-hidden">Telegram</span></a></li>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use></svg><span class="visually-hidden">Whatsapp</span></a></li>
                                            <li><a href="#" target="_blank"><svg class="icon icon-sm icon-white align-top"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use></svg><span class="visually-hidden">RSS</span></a></li>
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
