{{-- Guest personal area parity demo (for tests) --}}
<div class="it-user-wrapper nav-item dropdown">
    <a
        href="#"
        class="btn btn-primary btn-icon btn-full"
        data-bs-toggle="dropdown"
        data-element="personal-area-login"
        data-focus-mouse="false"
        aria-expanded="false"
        aria-controls="header-user-menu-guest-parity"
        id="header-user-toggle-guest-parity"
        title="{{ __('pub_theme::header.user.aria.toggle_menu.label') }}"
    >
        <span class="rounded-icon" aria-hidden="true">
            <svg class="icon icon-white">
                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
            </svg>
        </span>
        <span class="d-none d-lg-block">{{ __('pub_theme::ui.header_area_personale.parity_demo_user.label') }}</span>
        <svg class="icon icon-white d-none d-lg-block" aria-hidden="true">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
        </svg>
    </a>
    <div class="dropdown-menu" id="header-user-menu-guest-parity" aria-labelledby="header-user-toggle-guest-parity">
        <div class="row">
            <div class="col-12">
                <div class="link-list-wrapper">
                    <ul class="link-list">
                        <li>
                            <a class="dropdown-item list-item" href="{{ route('services.categories') }}">
                                <span>{{ __('pub_theme::header.user.dropdown.my_services.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ route('dashboard') }}">
                                <span>{{ __('pub_theme::header.user.dropdown.my_practices.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ route('notifications') }}">
                                <span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <span class="divider"></span>
                        </li>
                        <li>
                            <a class="dropdown-item list-item" href="{{ route('profile.edit') }}">
                                <span>{{ __('pub_theme::header.user.dropdown.settings.label') }}</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="list-item left-icon">
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
