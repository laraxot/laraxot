<div>
    <div class="flex items-center justify-between h-10 max-w-lg gap-4 px-4 mx-auto transition-colors bg-gray-100 rounded-lg search-box hover:bg-gray-50 focus:bg-gray-50">
        {{-- <input type="text" placeholder="Search here..." x-model.debounce.500ms="search" class="block w-full p-0 bg-transparent border-none outline-none focus:outline-none focus:ring-0" /> --}}

        <input type="text" wire:model.live="search" placeholder="{{ (string) __('pub_theme::headernav.search') }}..." class="block w-full p-0 bg-transparent border-none outline-none focus:outline-none focus:ring-0"/>


        <x-heroicon-o-magnifying-glass class="w-6 h-6" />
    </div>

    @if(!empty($results))
        <div class="absolute inset-0 z-50 top-16 h-min max-h-[80vh] max-w-lg p-2 space-y-2 overflow-hidden text-sm border border-white rounded-lg search-results bg-gray-50/85 backdrop-blur overflow-y-auto">
            <div class="space-y-4" {{-- x-show="filteredMarkets.length>0" --}} x-cloak>
                <div class="flex items-center justify-between px-2">
                    <span class="text-sm text-gray-400">Markets</span>
                    <a href="#" class="text-sm text-blue-500">{{ $results->count()/** @phpstan-ignore method.nonObject */ }}</a>
                </div>
                <ul class="space-y-1">
                    @foreach ($results as $article)
                        <div>
                            <li>
                                <a class="block p-2 text-base font-semibold transition rounded hover:bg-white hover:no-underline" 
                                    href="
                                    {{-- {{ url('articles/'.$article->slug) }} --}}
                                    {{ route('article.view', ['lang'=>$lang,'slug' => $article->slug ]) }}
                                    ">
                                    {{ $article->title }}
                                </a>
                            </li>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>