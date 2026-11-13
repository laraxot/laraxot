{{-- Bootstrap Italia Notifiche Component --}}

@php
$typeClass = match($type ?? 'info') {
    'success' => 'notification-success',
    'warning' => 'notification-warning',
    'danger' => 'notification-danger',
    'error' => 'notification-danger',
    default => 'notification-info',
};

$iconName = match($type ?? 'info') {
    'success' => 'it-check-circle',
    'warning' => 'it-warning-circle',
    'danger', 'error' => 'it-error',
    default => 'it-info-circle',
};
@endphp

<div class="notification notification-italia {{ $typeClass }} {{ ($dismissible ?? false) ? 'notification-dismissible' : '' }}"
     role="alert"
     aria-live="polite">
    <div class="notification-content">
        <svg class="icon notification-icon" aria-hidden="true">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#{{ $iconName }}"></use>
        </svg>
        <div class="notification-body">
            @if(isset($title))
            <h4 class="notification-title">{{ $title }}</h4>
            @endif
            @if(isset($message))
            <p class="notification-message">{{ $message }}</p>
            @endif
        </div>
    </div>
    @if($dismissible ?? false)
    <button type="button" class="btn-close notification-close" aria-label="{{ __('pub_theme::bootstrap_italia.notifiche.close_notification.label') }}">
        <span class="visually-hidden">{{ __('pub_theme::blocks_common.shared.close.label') }}</span>
    </button>
    @endif
</div>
