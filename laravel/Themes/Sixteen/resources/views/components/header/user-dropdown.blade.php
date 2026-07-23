<!-- User Dropdown Component (legacy — prefer sections/header/partials/user-dropdown) -->
<div x-data="{userOpen: false}"
     x-data="{avatarUrl: '{{  }}'}"
     x-data="{displayName: '{{  }}'}"
     @click.away="userOpen = false">
  <button @click="userOpen = !userOpen"
          class="nav-link dropdown-toggle header-auth-toggle"
          id="header-user-toggle">{{  }}</button>

  <div x-show="userOpen"
       class="dropdown-menu bg-white text-gray-800 rounded-md px-3 py-2"
       role="menu">
    <div class="link-list-wrapper">
      <ul class="link-list">
        <li>
          <a class="dropdown-item list-item" href="{{ route('services.categories') }}" role="menuitem">
            <x-ui::icon name="briefcase" class="icon-sm"/>
            {{ __('pub_theme::header.user.dropdown.my_services.label') }}
          </a>
        </li>
        <li>
          <a class="dropdown-item list-item" href="{{ route('dashboard') }}" role="menuitem">
            <x-ui::icon name="document-text" class="icon-sm"/>
            {{ __('pub_theme::header.user.dropdown.my_practices.label') }}
          </a>
        </li>
        <li>
          <a class="dropdown-item list-item" href="{{ route('notifications') }}" role="menuitem">
            <x-ui::icon name="bell" class="icon-sm"/>
            {{ __('pub_theme::header.user.dropdown.notifications.label') }}
          </a>
        </li>
        <li><span class="divider"></span></li>
        <li>
          <a class="dropdown-item list-item" href="{{ route('profile.edit') }}" role="menuitem">
            <x-ui::icon name="cog-6-tooth" class="icon-sm"/>
            {{ __('pub_theme::header.user.dropdown.settings.label') }}
          </a>
        </li>
        <li><span class="divider"></span></li>
        <li>
          <a class="dropdown-item list-item" href="{{ route('logout') }}" role="menuitem">
            <x-ui::icon name="arrow-right-on-rectangle" class="w-4 h-4 mr-2"/>
            {{ __('pub_theme::header.user.dropdown.logout.label') }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
