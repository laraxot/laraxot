@php($label ??= __('pub_theme::header.guest.cta.label'))
<a class="btn btn-primary btn-icon btn-full personal-area-login-cta" href="{{ route('login') }}" data-element="personal-area-login" aria-label="{{ $label }}">
    <span class="rounded-icon" aria-hidden="true">
        <svg class="icon icon-primary">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
        </svg>
    </span>
    <span class="d-none d-lg-block">{{ $label }}</span>
</a>
