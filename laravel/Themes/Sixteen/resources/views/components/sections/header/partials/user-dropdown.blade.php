<div class="it-user-wrapper nav-item dropdown">
    <button
        type="button"
        class="btn btn-primary btn-icon btn-full"
        id="header-user-toggle"
        data-bs-toggle="dropdown"
        data-bs-display="static"
        data-focus-mouse="false"
        aria-expanded="false"
        aria-controls="header-user-menu"
        aria-haspopup="true"
        aria-label="{{ $displayName }}"
    >
        <span class="rounded-icon" aria-hidden="true">
            @if ($avatarUrl)
                <img
                    src="{{ $avatarUrl }}"
                    referrerpolicy="no-referrer"
                    alt="{{ $displayName }}"
                    class="border rounded-circle icon-white"
                    width="20"
                    height="20"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <strong class="user-initial-fallback fw-bold text-uppercase">{{ $userInitial !== '' ? $userInitial : 'U' }}</strong>
            @endif
        </span>
        {{-- aria-label = nome visibile: nome accessibile a ogni larghezza, senza label-content-name-mismatch (axe). --}}
        <span class="d-none d-lg-block">{{ $displayName }}</span>
        <svg class="icon icon-white d-none d-lg-block" aria-hidden="true">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
        </svg>
    </button>

    <div
        class="dropdown-menu"
        id="header-user-menu"
        role="menu"
        aria-labelledby="header-user-toggle"
    >
        <div class="row">
            <div class="col-12">
                <div class="link-list-wrapper">
                    <ul class="link-list">
                        <li>
                            <a class="dropdown-item list-item" href="{{ route('services.categories') }}" role="menuitem">
                                <span>{{ __('pub_theme::header.user.dropdown.my_services.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaPratiche() }}" role="menuitem">
                                <span>{{ __('pub_theme::header.user.dropdown.my_practices.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaSeguite() }}" role="menuitem">
                                <span>{{ __('pub_theme::header.user.dropdown.followed_reports.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaNotifiche() }}" role="menuitem">
                                <span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
                                @if (($unreadNotificationsCount ?? 0) > 0)
                                    <span class="badge badge-primary ml-2" data-element="unread-notifications">{{ $unreadNotificationsCount }}<span class="visually-hidden"> {{ __('pub_theme::header.user.unread_notifications') }}</span></span>
                                @endif
                            </a>
                        </li>
                        <li><span class="divider"></span></li>
                        <li>
                            {{-- Canon: FrontofficeRoutes (mai localizeUrl / tickets.*). --}}
                            <a class="dropdown-item list-item" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaImpostazioni() }}" role="menuitem">
                                <span>{{ __('pub_theme::header.user.dropdown.settings.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="list-item left-icon" role="menuitem">
                                    <svg class="icon icon-primary icon-sm left" aria-hidden="true">
                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-external-link"></use>
                                    </svg>
                                    <span class="fw-bold">{{ __('pub_theme::header.user.dropdown.logout.label') }}</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
