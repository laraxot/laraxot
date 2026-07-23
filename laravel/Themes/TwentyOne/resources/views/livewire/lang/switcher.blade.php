@php
    $locales = [
        'it' => 'Italiano',
        'en' => 'English'
    ];
    
    $base = str_replace(url('/'),'',url()->full());
    $base = Str::of($base)->after('/'.$lang)->toString();
@endphp

<div>
    <x-filament::dropdown>
        <x-slot name="trigger">
            <button type="bottom" class="grid py-3 text-sm font-semibold transition rounded-lg place-items-center">
                <div class="flex items-center space-x-2">
                    {{--  
                    <div>{{ $_theme->flag($lang) }}</div>
                    --}}
                    <x-filament::icon
					    icon="ui-flags.{{ $lang }}"
					    class="size-5"
				    />
                    <span class="hidden sm:block">{{ $locales[$lang] }}</span>
                    <x-heroicon-o-chevron-down class="hidden size-4 sm:block"/>
                </div>
            </button>
        </x-slot>
        <x-filament::dropdown.list>
            @foreach($locales as $key => $locale)
                <x-filament::dropdown.list.item  href="{{ url('/'.$key.$base) }}" tag="a">
                    <div class="flex items-center space-x-2">
                        {{--  
                        <div>{{ $_theme->flag($key) }}</div>
                        --}}
                        <x-filament::icon
					        icon="ui-flags.{{ $key }}"
					        class="size-5"
				        />
                        <span>{{ $locale }}</span>
                    </div>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>