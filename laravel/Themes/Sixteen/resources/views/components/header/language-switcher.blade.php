<!-- Public Language Switcher Component -->
<div x-data="{langOpen: false}">
  <button @click="langOpen = !langOpen" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
    <span>ITA</span>
    <x-ui::icon name="chevron-down" x-show="!langOpen"/>
    <x-ui::icon name="chevron-up" x-show="langOpen"/>
  </button>
  <div class="dropdown-menu dropdown-menu-right"
       x-show="langOpen"
       x-transition:enter="transition ease-out duration-200"
       x-transition:leave="transition ease-in duration-150"
       role="menu">
    <a href="#" class="dropdown-item list-item"
x-on:click="lang = 'it'" aria-label="Seleziona lingua italiana">ITA</a>
    <a href="#" class="dropdown-item list-item"
x-on:click="lang = 'en'" aria-label="Seleziona lingua inglese">ENG</a>
  </div>
</div>
