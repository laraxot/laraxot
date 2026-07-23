<div class="fi-section-content-ctn">
    <div class="fi-section-content p-6">
        <div ax-load="" ax-load-src="http://fixcity.local/js/filament/tables/components/table.js?v=3.2.92.0" x-data="table" class="fi-ta">
<div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
<div x-bind:hidden="! (true || (selectedRecords.length &amp;&amp; 0))" x-show="true || (selectedRecords.length &amp;&amp; 0)" class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
        <!--[if BLOCK]><![endif]-->                <div class="fi-ta-header flex flex-col gap-3 p-4 sm:px-6 sm:flex-row sm:items-center">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]-->        <!--[if BLOCK]><![endif]-->    <div class="fi-ta-actions flex shrink-0 items-center gap-3 flex-wrap justify-start ms-auto sm:ms-auto">
    <!--[if BLOCK]><![endif]-->            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action" type="button" wire:loading.attr="disabled" wire:click="mountTableAction('create')">
<!--[if BLOCK]><![endif]-->        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->            <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin fi-btn-icon transition duration-75 h-5 w-5 text-white" wire:loading.delay.default="" wire:target="mountTableAction('create')">
<path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
<path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
</svg>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
<!--[if ENDBLOCK]><![endif]-->

<span class="fi-btn-label">
    Nuovo
</span>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</button>

    <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
<!--[if ENDBLOCK]><![endif]-->
</div>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <div x-show="true || (selectedRecords.length &amp;&amp; 0)" class="fi-ta-header-toolbar flex items-center justify-between gap-x-4 px-4 py-3 sm:px-6">


            <div class="flex shrink-0 items-center gap-x-4">


                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->



                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->



                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->


            </div>

            <!--[if BLOCK]><![endif]-->                    <div class="ms-auto flex items-center gap-x-4">


                    <!--[if BLOCK]><![endif]-->                            <div x-id="['input']" class="fi-ta-search-field">
<label x-bind:for="$id('input')" class="sr-only" for="input-1">
    Cerca
</label>

<div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500">
<!--[if BLOCK]><![endif]-->        <div class="items-center gap-x-3 ps-3 flex pe-2">
        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->                <!--[if BLOCK]><![endif]-->    <svg style=";" wire:loading.remove.delay.default="1" wire:target="tableSearch" class="fi-input-wrp-icon h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
<path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->                <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin fi-input-wrp-icon h-5 w-5 text-gray-400 dark:text-gray-500" wire:loading.delay.default="" wire:target="tableSearch">
<path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
<path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
</svg>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </div>
<!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <input class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-0 pe-3" autocomplete="off" placeholder="Cerca" type="search" wire:key="Yu4Edkd6YF83diQHaJNF.table.tableSearch.field.input" wire:model.live.debounce.500ms="tableSearch" x-bind:id="$id('input')" x-on:keyup="if ($event.key === 'Enter') { $wire.$refresh() }" id="input-1">
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>
</div>
                    <!--[if ENDBLOCK]><![endif]-->



                    <!--[if BLOCK]><![endif]-->                            <!--[if BLOCK]><![endif]-->                                <!--[if BLOCK]><![endif]-->    <div x-data="{
    toggle: function (event) {
        $refs.panel.toggle(event)
    },

    open: function (event) {
        $refs.panel.open(event)
    },

    close: function (event) {
        $refs.panel.close(event)
    },
}" class="fi-dropdown fi-ta-filters-dropdown" wire:key="Yu4Edkd6YF83diQHaJNF.table.filters">
<div x-on:click="toggle" class="fi-dropdown-trigger flex cursor-pointer">
    <!--[if BLOCK]><![endif]-->    <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-ac-action fi-ac-icon-btn-action" title="Filtro" type="button">
    <!--[if BLOCK]><![endif]-->            <span class="sr-only">
            Filtro
        </span>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
<path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->            <div class="fi-icon-btn-badge-ctn absolute start-full top-1 z-[1] w-max -translate-x-1/2 -translate-y-1/2 rounded-md bg-white dark:bg-gray-900 rtl:translate-x-1/2">
            <span style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-0.5 min-w-[theme(spacing.4)] tracking-tighter fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-primary">
<!--[if BLOCK]><![endif]-->        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
<!--[if ENDBLOCK]><![endif]-->

<span class="grid">
    <span class="truncate">
        0
    </span>
</span>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</span>
        </div>
    <!--[if ENDBLOCK]><![endif]-->
</button>
<!--[if ENDBLOCK]><![endif]-->
</div>

        <div x-float.placement.bottom-end.flip.shift.offset="{ offset: 8,  }" x-ref="panel" x-transition:enter-start="opacity-0" x-transition:leave-end="opacity-0" wire:ignore.self="" wire:key="Yu4Edkd6YF83diQHaJNF.table.filters.panel" class="fi-dropdown-panel absolute z-10 w-screen divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10 !max-w-xs" style="display: none;">
        <div class="fi-ta-filters grid gap-y-4 p-6">
<div class="flex items-center justify-between">
    <h4 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
        Filtri
    </h4>

    <div>
        <button class="fi-link group/link relative inline-flex items-center justify-center outline-none fi-size-md fi-link-size-md gap-1.5 fi-color-custom fi-color-danger" type="button" wire:loading.attr="disabled" wire:target="resetTableFiltersForm" wire:click="resetTableFiltersForm" wire:loading.remove.delay.default="">

                        <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin fi-link-icon h-5 w-5 text-custom-600 dark:text-custom-400" style="--c-400:var(--danger-400);--c-600:var(--danger-600);" wire:loading.delay.default="" wire:target="resetTableFiltersForm">
<path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
<path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
</svg>

    <span class="font-semibold group-hover/link:underline group-focus-visible/link:underline text-sm text-custom-600 dark:text-custom-400" style="--c-400:var(--danger-400);--c-600:var(--danger-600);">
        Reimposta
    </span>


        </button>
        <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 text-gray-400 dark:text-gray-500" wire:loading.delay.default="" wire:target="tableFilters,applyTableFilters,resetTableFiltersForm">
<path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
<path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
</svg>
    </div>
</div>

<div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6" x-data="{}" x-on:form-validation-error.window="if ($event.detail.livewireId !== 'Yu4Edkd6YF83diQHaJNF') {
            return
        }

        $nextTick(() => {
            let error = $el.querySelector('[data-validation-error]')

            if (! error) {
                return
            }

            let elementToExpand = error

            while (elementToExpand) {
                elementToExpand.dispatchEvent(new CustomEvent('expand'))

                elementToExpand = elementToExpand.parentNode
            }

            setTimeout(
                () =>
                    error.closest('[data-field-wrapper]').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                        inline: 'start',
                    }),
                200,
            )
    })">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: span 2 / span 2;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <div>
<div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(4, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <fieldset class="fi-fieldset rounded-xl border border-gray-200 p-6 dark:border-white/10">
<!--[if BLOCK]><![endif]-->        <legend class="-ms-2 px-2 text-sm font-medium leading-6 text-gray-950 dark:text-white">
        Radius Filter
    </legend>
<!--[if ENDBLOCK]><![endif]-->

<div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <div>
<div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" >
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" >
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.geocomplete.Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete">
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" >
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.geocomplete.Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete">
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" >
<!--[if BLOCK]><![endif]-->                <div data-field-wrapper="" class="fi-fo-field-wrp">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="grid gap-y-2">
    <!--[if BLOCK]><![endif]-->            <div class="flex items-center gap-x-3 justify-between ">
            <!--[if BLOCK]><![endif]-->                    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="tableFilters.location.geocomplete">


<span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">

    Indirizzo<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</span>


</label>
            <!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->            <div class="grid auto-cols-fr gap-y-2">
            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <div class="w-full" ax-load="" ax-load-src="http://fixcity.local/js/cheesegrits/filament-google-maps/components/filament-google-maps-geocomplete.js?v=3.0.14.0" x-data="filamentGoogleGeocomplete({
                    setStateUsing: async (path, state) => {
                        return await $wire.set(path, state)
                    },
                    reverseGeocodeUsing: (results) => {
                        $wire.reverseGeocodeUsing('tableFilters.location.geocomplete', results)
                    },
                    filterName: 'tableFilters.location',
                    statePath: 'tableFilters.location.geocomplete',
                    isLocation: false,
                    reverseGeocodeFields: [],
                    hasReverseGeocodeUsing: false,
                    latLngFields: [],
                    types: JSON.parse('[\u0022geocode\u0022]'),
                    placeField: 'formatted_address',
                    countries: [],
                    debug: false,
                    gmaps: 'http:\/\/maps.googleapis.com\/maps\/api\/js?key=\u0026libraries=places\u0026v=weekly',
                })" wire:ignore="">


































        <input class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3 pac-target-input" id="tableFilters.location.geocomplete" type="text" wire:model.blur="tableFilters.location.geocomplete" placeholder="Inserisci una posizione" autocomplete="off">

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->
</div>
</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>

    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <div>
<div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.radius.Filament\Forms\Components\TextInput">
<!--[if BLOCK]><![endif]-->                <div data-field-wrapper="" class="fi-fo-field-wrp">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="grid gap-y-2">
    <!--[if BLOCK]><![endif]-->            <div class="flex items-center gap-x-3 justify-between ">
            <!--[if BLOCK]><![endif]-->                    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="tableFilters.location.radius">


<span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">

    Distanza<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</span>


</label>
            <!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->            <div class="grid auto-cols-fr gap-y-2">
            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <input class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3" id="tableFilters.location.radius" inputmode="decimal" step="any" type="number" wire:model.blur="tableFilters.location.radius">
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->
</div>
</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>

    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.unit.Filament\Forms\Components\Select">
<!--[if BLOCK]><![endif]-->                <div data-field-wrapper="" class="fi-fo-field-wrp">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="grid gap-y-2">
    <!--[if BLOCK]><![endif]-->            <div class="flex items-center gap-x-3 justify-between ">
            <!--[if BLOCK]><![endif]-->                    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="tableFilters.location.unit">


<span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">

    Unità<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</span>


</label>
            <!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]-->            <div class="grid auto-cols-fr gap-y-2">
            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-select">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <!--[if BLOCK]><![endif]-->            <select class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 ps-3" id="tableFilters.location.unit" wire:model.live="tableFilters.location.unit">
<!--[if BLOCK]><![endif]-->                    <option value="">
                    <!--[if BLOCK]><![endif]-->                            Seleziona un'opzione
                    <!--[if ENDBLOCK]><![endif]-->
                </option>
            <!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]-->                    <!--[if BLOCK]><![endif]-->                        <option value="mi">
                        <!--[if BLOCK]><![endif]-->                                Miglia
                        <!--[if ENDBLOCK]><![endif]-->
                    </option>
                <!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]-->                        <option value="km">
                        <!--[if BLOCK]><![endif]-->                                Chilometri
                        <!--[if ENDBLOCK]><![endif]-->
                    </option>
                <!--[if ENDBLOCK]><![endif]-->
            <!--[if ENDBLOCK]><![endif]-->
</select>
    <!--[if ENDBLOCK]><![endif]-->
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
    <!--[if ENDBLOCK]><![endif]-->
</div>
</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>

</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>

    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <div>
<div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]-->
    <div style="--col-span-default: span hidden / span hidden;" class="hidden col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.latitude.Filament\Forms\Components\Hidden">
<!--[if BLOCK]><![endif]-->                <input class="fi-fo-hidden" id="tableFilters.location.latitude" type="hidden" wire:model.live="tableFilters.location.latitude" value="">

        <!--[if ENDBLOCK]><![endif]-->
</div>

    <div style="--col-span-default: span hidden / span hidden;" class="hidden col-[--col-span-default]" wire:key="Yu4Edkd6YF83diQHaJNF.tableFilters.location.longitude.Filament\Forms\Components\Hidden">
<!--[if BLOCK]><![endif]-->                <input class="fi-fo-hidden" id="tableFilters.location.longitude" type="hidden" wire:model.live="tableFilters.location.longitude" value="">

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>

</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>

</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>
</fieldset>

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>

</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>

    <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
<!--[if BLOCK]><![endif]-->                <div>
<div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

</div>

        <!--[if ENDBLOCK]><![endif]-->
</div>
<!--[if ENDBLOCK]><![endif]-->
</div>


<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>
    </div>
</div>
<!--[if ENDBLOCK]><![endif]-->
                        <!--[if ENDBLOCK]><![endif]-->



                        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->


                    <!--[if ENDBLOCK]><![endif]-->
                </div>
            <!--[if ENDBLOCK]><![endif]-->


        </div>
    </div>

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
        <!--[if BLOCK]><![endif]-->

                    <div class="fi-ta-empty-state px-6 py-12">
<div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
    <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
        <!--[if BLOCK]><![endif]-->    <svg class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
</svg><!--[if ENDBLOCK]><![endif]-->
    </div>

    <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
Nessun risultato
</h4>

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>
</div>


        <!--[if ENDBLOCK]><![endif]-->
    </div>

    <!--[if BLOCK]><![endif]-->            <nav aria-label="Navigazione paginazione" role="navigation" class="fi-pagination grid grid-cols-[1fr_auto_1fr] items-center gap-x-3 fi-simple fi-ta-pagination px-3 py-3 sm:px-6">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]-->        <div class="col-start-2 justify-self-center">
        <label class="fi-pagination-records-per-page-select fi-compact">
            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <select class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 ps-3" wire:model.live="tableRecordsPerPage">
<!--[if BLOCK]><![endif]-->                            <option value="10">
                            10
                        </option>
                                                <option value="25">
                            25
                        </option>
                                                <option value="50">
                            50
                        </option>
                                                <option value="100">
                            100
                        </option>
                    <!--[if ENDBLOCK]><![endif]-->
</select>
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

            <span class="sr-only">
                per pagina
            </span>
        </label>

        <label class="fi-pagination-records-per-page-select">
            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500">
<!--[if BLOCK]><![endif]-->        <div class="items-center gap-x-3 ps-3 flex border-e border-gray-200 pe-3 ps-3 dark:border-white/10">
        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->                <span class="fi-input-wrp-label whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                per pagina
            </span>
        <!--[if ENDBLOCK]><![endif]-->
    </div>
<!--[if ENDBLOCK]><![endif]-->

<div class="min-w-0 flex-1">
    <select class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 ps-3" wire:model.live="tableRecordsPerPage">
<!--[if BLOCK]><![endif]-->                            <option value="10">
                            10
                        </option>
                                                <option value="25">
                            25
                        </option>
                                                <option value="50">
                            50
                        </option>
                                                <option value="100">
                            100
                        </option>
                    <!--[if ENDBLOCK]><![endif]-->
</select>
</div>

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>
        </label>
    </div>
<!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</nav>
    <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>

<!--[if BLOCK]><![endif]-->    <form wire:submit.prevent="callMountedAction">

    <div aria-modal="true" role="dialog" x-data="{
    isOpen: false,

    livewire: null,

    close: function () {
        this.isOpen = false

        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-closed', { id: 'Yu4Edkd6YF83diQHaJNF-action' }),
        )


    },

    open: function () {
        this.isOpen = true



        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-opened', { id: 'Yu4Edkd6YF83diQHaJNF-action' }),
        )
    },
}" x-on:close-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-action') close()" x-on:open-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-action') open()" x-trap.noscroll="isOpen" x-bind:class="{
    'fi-modal-open': isOpen,
}" class="fi-modal block">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div x-show="isOpen" style="display: none;">
    <div aria-hidden="true" x-show="isOpen" x-transition.duration.300ms.opacity="" class="fi-modal-close-overlay fixed inset-0 z-40 bg-gray-950/50 dark:bg-gray-950/75" style="display: none;"></div>

    <div class="fixed inset-0 z-40 overflow-y-auto cursor-pointer">
        <div x-ref="modalContainer" x-on:click.self="
                    document.activeElement.selectionStart === undefined &amp;&amp;
                        document.activeElement.selectionEnd === undefined &amp;&amp;
                        $dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-action' })
                " class="relative grid min-h-full grid-rows-[1fr_auto_1fr] justify-items-center sm:grid-rows-[1fr_auto_3fr] p-4" x-on:closed-form-component-action-modal.window="if (($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') &amp;&amp; $wire.mountedActions.length) open()" x-on:modal-closed.stop="const mountedActionShouldOpenModal = false


            if (! mountedActionShouldOpenModal) {
                return
            }

            if ($wire.mountedFormComponentActions.length) {
                return
            }

            $wire.unmountAction(false)" x-on:opened-form-component-action-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') close()">
            <div x-data="{ isShown: false }" x-init="
                    $nextTick(() => {
                        isShown = isOpen
                        $watch('isOpen', () => (isShown = isOpen))
                    })
                " x-on:keydown.window.escape="$dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-action' })" x-show="isShown" x-transition:enter="duration-300" x-transition:leave="duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0" class="fi-modal-window pointer-events-auto relative row-start-2 flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl hidden max-w-sm" style="display: none;">
                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
</div>
</form>

<!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]-->    <form wire:submit.prevent="callMountedTableAction">

    <div aria-modal="true" role="dialog" x-data="{
    isOpen: false,

    livewire: null,

    close: function () {
        this.isOpen = false

        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-closed', { id: 'Yu4Edkd6YF83diQHaJNF-table-action' }),
        )


    },

    open: function () {
        this.isOpen = true



        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-opened', { id: 'Yu4Edkd6YF83diQHaJNF-table-action' }),
        )
    },
}" x-on:close-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-table-action') close()" x-on:open-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-table-action') open()" x-trap.noscroll="isOpen" x-bind:class="{
    'fi-modal-open': isOpen,
}" class="fi-modal block">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div x-show="isOpen" style="display: none;">
    <div aria-hidden="true" x-show="isOpen" x-transition.duration.300ms.opacity="" class="fi-modal-close-overlay fixed inset-0 z-40 bg-gray-950/50 dark:bg-gray-950/75" style="display: none;"></div>

    <div class="fixed inset-0 z-40 overflow-y-auto cursor-pointer">
        <div x-ref="modalContainer" x-on:click.self="
                    document.activeElement.selectionStart === undefined &amp;&amp;
                        document.activeElement.selectionEnd === undefined &amp;&amp;
                        $dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-table-action' })
                " class="relative grid min-h-full grid-rows-[1fr_auto_1fr] justify-items-center sm:grid-rows-[1fr_auto_3fr] p-4" x-on:closed-form-component-action-modal.window="if (($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') &amp;&amp; $wire.mountedTableActions.length) open()" x-on:modal-closed.stop="const mountedTableActionShouldOpenModal = false


            if (! mountedTableActionShouldOpenModal) {
                return
            }

            if ($wire.mountedFormComponentActions.length) {
                return
            }

            $wire.unmountTableAction(false)" x-on:opened-form-component-action-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') close()">
            <div x-data="{ isShown: false }" x-init="
                    $nextTick(() => {
                        isShown = isOpen
                        $watch('isOpen', () => (isShown = isOpen))
                    })
                " x-on:keydown.window.escape="$dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-table-action' })" x-show="isShown" x-transition:enter="duration-300" x-transition:leave="duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0" class="fi-modal-window pointer-events-auto relative row-start-2 flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl hidden max-w-sm" style="display: none;">
                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
</div>
</form>

<form wire:submit.prevent="callMountedTableBulkAction">

    <div aria-modal="true" role="dialog" x-data="{
    isOpen: false,

    livewire: null,

    close: function () {
        this.isOpen = false

        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-closed', { id: 'Yu4Edkd6YF83diQHaJNF-table-bulk-action' }),
        )


    },

    open: function () {
        this.isOpen = true



        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-opened', { id: 'Yu4Edkd6YF83diQHaJNF-table-bulk-action' }),
        )
    },
}" x-on:close-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-table-bulk-action') close()" x-on:open-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-table-bulk-action') open()" x-trap.noscroll="isOpen" x-bind:class="{
    'fi-modal-open': isOpen,
}" class="fi-modal block">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div x-show="isOpen" style="display: none;">
    <div aria-hidden="true" x-show="isOpen" x-transition.duration.300ms.opacity="" class="fi-modal-close-overlay fixed inset-0 z-40 bg-gray-950/50 dark:bg-gray-950/75" style="display: none;"></div>

    <div class="fixed inset-0 z-40 overflow-y-auto cursor-pointer">
        <div x-ref="modalContainer" x-on:click.self="
                    document.activeElement.selectionStart === undefined &amp;&amp;
                        document.activeElement.selectionEnd === undefined &amp;&amp;
                        $dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-table-bulk-action' })
                " class="relative grid min-h-full grid-rows-[1fr_auto_1fr] justify-items-center sm:grid-rows-[1fr_auto_3fr] p-4" x-on:closed-form-component-action-modal.window="if (($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') &amp;&amp; $wire.mountedTableBulkAction) open()" x-on:modal-closed.stop="const mountedTableBulkActionShouldOpenModal = false


            if (! mountedTableBulkActionShouldOpenModal) {
                return
            }

            if ($wire.mountedFormComponentActions.length) {
                return
            }

            $wire.unmountTableBulkAction(false)" x-on:opened-form-component-action-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF') close()">
            <div x-data="{ isShown: false }" x-init="
                    $nextTick(() => {
                        isShown = isOpen
                        $watch('isOpen', () => (isShown = isOpen))
                    })
                " x-on:keydown.window.escape="$dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-table-bulk-action' })" x-show="isShown" x-transition:enter="duration-300" x-transition:leave="duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0" class="fi-modal-window pointer-events-auto relative row-start-2 flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl hidden max-w-sm" style="display: none;">
                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
</div>
</form>

<!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]-->
<form wire:submit.prevent="callMountedFormComponentAction">
    <div aria-modal="true" role="dialog" x-data="{
    isOpen: false,

    livewire: null,

    close: function () {
        this.isOpen = false

        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-closed', { id: 'Yu4Edkd6YF83diQHaJNF-form-component-action' }),
        )


    },

    open: function () {
        this.isOpen = true



        this.$refs.modalContainer.dispatchEvent(
            new CustomEvent('modal-opened', { id: 'Yu4Edkd6YF83diQHaJNF-form-component-action' }),
        )
    },
}" x-on:close-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-form-component-action') close()" x-on:open-modal.window="if ($event.detail.id === 'Yu4Edkd6YF83diQHaJNF-form-component-action') open()" x-trap.noscroll="isOpen" x-bind:class="{
    'fi-modal-open': isOpen,
}" class="fi-modal block">
<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

<div x-show="isOpen" style="display: none;">
    <div aria-hidden="true" x-show="isOpen" x-transition.duration.300ms.opacity="" class="fi-modal-close-overlay fixed inset-0 z-40 bg-gray-950/50 dark:bg-gray-950/75" style="display: none;"></div>

    <div class="fixed inset-0 z-40 overflow-y-auto cursor-pointer">
        <div x-ref="modalContainer" x-on:click.self="
                    document.activeElement.selectionStart === undefined &amp;&amp;
                        document.activeElement.selectionEnd === undefined &amp;&amp;
                        $dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-form-component-action' })
                " class="relative grid min-h-full grid-rows-[1fr_auto_1fr] justify-items-center sm:grid-rows-[1fr_auto_3fr] p-4" x-on:modal-closed.stop="const mountedFormComponentActionShouldOpenModal = false


            if (mountedFormComponentActionShouldOpenModal) {
                $wire.unmountFormComponentAction(false)
            }">
            <div x-data="{ isShown: false }" x-init="
                    $nextTick(() => {
                        isShown = isOpen
                        $watch('isOpen', () => (isShown = isOpen))
                    })
                " x-on:keydown.window.escape="$dispatch('close-modal', { id: 'Yu4Edkd6YF83diQHaJNF-form-component-action' })" x-show="isShown" x-transition:enter="duration-300" x-transition:leave="duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0" class="fi-modal-window pointer-events-auto relative row-start-2 flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mx-auto rounded-xl hidden max-w-sm" style="display: none;">
                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
</div>
</form>

<!--[if ENDBLOCK]><![endif]-->
</div>
    </div>

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</div>


<button
                                style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-1.5 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray ms-auto hidden lg:flex" title="Comprimi sidebar" type="button" x-cloak="x-cloak" x-data="{}" x-on:click="$store.sidebar.close()" x-show="$store.sidebar.isOpen"
    >
        <!--[if BLOCK]><![endif]-->            <span class="sr-only">
                Comprimi sidebar
            </span>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
</svg><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </button>
