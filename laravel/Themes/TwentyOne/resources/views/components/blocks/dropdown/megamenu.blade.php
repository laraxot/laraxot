<?php

use Illuminate\Support\Facades\Cache;
use Livewire\Volt\Component;

new class extends Component {
    public $model;

    public function with(): array
    {
        $cacheKey = 'pub_theme.megamenu.'.md5((string) $this->model.'|'.app()->getLocale());

        return [
            'items' => Cache::remember($cacheKey, now()->addMinutes(10), fn () => $this->model::tree()->get()->toTree()),
        ];
    }
}
?>
<div>
    @volt()
    <div>
        <button class="flex items-center space-x-1 text-sm font-semibold text-gray-600 hover:text-blue-600" data-dropdown-toggle="dropdown-markets">
            <x-heroicon-o-globe-europe-africa class="size-6" />
            <span>{{ __('pub_theme::headernav.markets') }}</span>
            <x-heroicon-o-chevron-down class="size-4" />
        </button>
        <div id="dropdown-markets" class="absolute z-20 hidden overflow-hidden rounded-lg border border-white bg-white p-2 text-sm">
            <ul class="flex max-w-4xl flex-wrap gap-1">
                @foreach($items as $item)
                <li class="flex flex-col w-[220px] p-3 space-y-4 transition-colors rounded justify-content-between hover:ring-1 hover:ring-gray-100">
                    <a href="
                        {{ route('category.view', ['lang'=>$lang,'slug' => $item->slug ]) }}
                        ">
                        <div class="flex items-center space-x-2">
                            <span class="grid text-blue-600 bg-blue-100 rounded place-items-center size-8">
                                @if($item->icon == null)
                                <x-heroicon-o-question-mark-circle class="size-6" />
                                @elseif(preg_match('/^[a-z0-9\-_]+$/i', $item->icon))
                                {{-- Se è un nome valido per una SVG --}}
                                @svg($item->icon, 'size-6')
                                @else
                                {{-- Altrimenti mostra il contenuto come emoji o testo --}}
                                <span class="text-xl">{{ $item->icon }}</span>
                                @endif
                            </span>
                            <span class="font-semibold">{{ $item->title }}</span>
                        </div>
                    </a>
                    <ul class="space-y-1 grow">
                        @foreach($item->children as $child)
                        <li>
                            <a class="block p-1 -ms-1 hover:text-blue-600" href="
                                    {{ route('category.view', ['lang'=>$lang,'slug' => $child->slug ]) }}
                                    ">
                                {{ $child->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endvolt
</div>
