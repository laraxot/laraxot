@php
  /** @var array $headerNavSecondary */
  /** @var string $headerNavTopicsUrl */
  /** @var callable $headerNavItemIsActive */
  /** @var callable $headerFolioUrl */
@endphp

<nav aria-label="{{ __('pub_theme::header.center.nav.secondary_aria.label') }}">
  <ul class="navbar-nav navbar-secondary">
    @foreach($headerNavSecondary as $headerNavSecItem)
      <li class="nav-item">
        <a class="nav-link{{ $headerNavItemIsActive($headerNavSecItem) ? ' active' : '' }}"
           href="{{ $headerFolioUrl((string) ($headerNavSecItem['url'] ?? '#')) }}"
           @if(! empty($headerNavSecItem['data_element'])) data-element="{{ $headerNavSecItem['data_element'] }}" @endif>
          <span>{{ $headerNavSecItem['label'] ?? '' }}</span>
        </a>
      </li>
    @endforeach
    <li class="nav-item">
      <a class="nav-link" href="{{ $headerNavTopicsUrl }}" data-element="all-topics">
        <span>{{ __('pub_theme::header.center.nav.argomenti.label') }}
          <svg class="icon icon-sm">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-chevron-right"></use>
          </svg>
        </span>
      </a>
    </li>
  </ul>
</nav>
