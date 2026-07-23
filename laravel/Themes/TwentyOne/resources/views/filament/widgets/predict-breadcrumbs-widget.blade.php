<div>
    <nav class="text-sm text-gray-600" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            @if ($predict->category)
                @foreach ($predict->category->ancestorsAndSelf()->get()->reverse() as $category)
                    <li>
                        <div class="flex items-center">
                            <a href="{{ route('category.view', ['slug' => $category->slug]) }}"
                                class="text-blue-600 hover:underline transition">
                                {{ $category->title }}
                            </a>
                            <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L11.586 9 7.293 4.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </li>
                @endforeach
            @endif
            <li class="text-gray-500">
                {{ $predict->title }}
            </li>
        </ol>
    </nav>
</div>
