<div class="container max-w-6xl p-6 mx-auto space-y-4">
    <p class="flex items-center gap-3 mb-4 text-sm text-gray-400">
        <span>All</span>
        @foreach($category->ancestors()->breadthFirst()->get() as $ancestor)
            <span>
                <x-heroicon-o-chevron-right width="15px"/>
            </span>
            <a class="text-blue-500" href="
                {{-- {{ url('categories/'.$ancestor->slug) }} --}}
                {{ route('category.view', ['lang'=>$lang,'slug' => $ancestor->slug ]) }}
                ">{{ $ancestor->title }}</a>
        @endforeach
    </p>
    <h2 class="flex items-center space-x-2 text-2xl font-semibold">
        {{ $category->title }}
    </h2>
    <ul class="flex flex-wrap gap-2">
        @foreach($category->descendants()->get() as $descendant)
        <li>
            <a type="button" href="
                {{-- {{ url('categories/'.$descendant->slug) }} --}}
                {{ route('category.view', ['lang'=>$lang,'slug' => $descendant->slug ]) }}
                " class="px-3 py-1 transition-colors bg-gray-200 rounded hover:bg-gray-300">
                <span>{{ $descendant->title }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>