<div class="flex items-center gap-2">
  {{-- {{ dddx($_theme->getMenu('main')) }} --}}
  @include('pub_theme::layouts.headernav.links.categories')

  <a class="flex-col items-center hidden p-2 capitalize transition-all ease-out bg-transparent rounded-lg navlink lg:flex hover:bg-white text-neutral-3"
    href="{{ url('pages/leaderboard') }}">
    <x-heroicon-o-trophy class="w-6 h-6" />
    <span class="navlink-text">
        {{  __('txt.leaderboard') }}

    </span>
  </a>

</div
