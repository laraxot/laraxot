@php
  /** @var array $headerNavItems */
  /** @var callable $headerNavItemIsActive */
  /** @var callable $headerFolioUrl */
@endphp

<nav aria-label="{{ __('pub_theme::header.center.nav.primary_aria.label') }}">
  <ul class="navbar-nav" data-element="main-navigation">
    @foreach($headerNavItems as $item)
      <li class="nav-item">
        <a class="nav-link{{ $headerNavItemIsActive($item) ? ' active' : '' }}"
           href="{{ $headerFolioUrl((string) ($item['url'] ?? '#')) }}"
           @if(! empty($item['data_element'])) data-element="{{ $item['data_element'] }}" @endif>
          <span>{{ $item['label'] ?? '' }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</nav>
