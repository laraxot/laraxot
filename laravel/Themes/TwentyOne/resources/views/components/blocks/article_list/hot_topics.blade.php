<div class="container max-w-6xl p-6 mx-auto space-y-4">
    <h2 class="flex items-center space-x-2 text-xl font-semibold">
        <x-heroicon-o-fire class="text-blue-500 size-8"/>
        <span>{{ $title }}</span>
    </h2>
    <section aria-label="{{ $title }}">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($_theme->getMethodData($method) as $category)
                <a class="bg-white rounded-lg overflow-clip" href="{{ route('category.view', ['lang'=>$lang,'slug' => $category['slug'] ]) }}">
                    <div class="h-[240px] relative">
                        <img class="absolute inset-0 object-cover w-full h-full" 
                            src="{{ ($category['image'] != '') ? $category['image'] : 'https://placehold.co/300x200'  }}" 
                            alt="{{ $category['title'] }}"/>
                    </div>
                    <div class="flex flex-col justify-between p-4 space-y-2">
                        <span class="inline px-3 py-1 text-blue-600 rounded w-max bg-blue-200/20">{{ $category['ratings_sum'] }} {{ __('blog::article.navigation.plural') }}</span>
                        <h3 class="font-semibold grow">{{ $category['title'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>
