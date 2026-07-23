<div class="flex items-center space-x-1 md:order-2 md:space-x-0 rtl:space-x-reverse">
  <button type="button" data-dropdown-toggle="profile-dropdown-menu"
      class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-900 rounded-lg cursor-pointer dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
      <x-filament::avatar src="{{ $_profile->getAvatarUrl() }}" />
  </button>

  <!-- Dropdown -->
  <div class="hidden z-50 my-4 text-base list-none bg-white rounded-lg divide-y divide-gray-100 shadow dark:bg-gray-700"
      id="profile-dropdown-menu">
      <ul class="py-2 font-medium" role="none">
          @can('go-to-admin')
          <li>
              <a href="{{ url('idoteca/admin/') }}"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                  role="menuitem">
                  <div class="inline-flex items-center whitespace-nowrap">
                      <x-fas-toolbox class="w-3.5 h-3.5 me-2" />
                      Area admin
                  </div>
              </a>
          </li>
          @endcan
          <li>
              <a href="{{ route('logout') }}"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                  role="menuitem">
                  <div class="inline-flex items-center">
                      <x-fas-sign-out-alt class="w-3.5 h-3.5 me-2" />
                      Log Out
                  </div>
              </a>
          </li>
      </ul>
  </div>
</div>
