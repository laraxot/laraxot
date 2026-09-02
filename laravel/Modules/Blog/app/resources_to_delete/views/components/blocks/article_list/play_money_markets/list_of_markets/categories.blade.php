<div class="pt-4">
    <ul class="flex flex-wrap items-center">
        @foreach($article->categories as $category)
            <li class="flex items-center gap-2">
                @if($loop->index > 0)
                    <svg class="ms-2" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 15" width="5px">
                        <path d="M.175 1.238L1.399 0 9 7.523 1.397 15l-1.22-1.242L6.52 7.52.175 1.24z"
                            fill="currentColor"></path>
                    </svg>
                @endif
                <a href="
                    {{-- {{ url('categories/'.$category->slug) }} --}}
                    {{ route('category.view', ['lang'=>$lang,'slug' => $category->slug ]) }}
                    "
                    @if($loop->index + 1 == $article->categories->count())
                        class="text-base font-bold leading-4 rounded-lg hover:text-blue-1"
                    @else
                        class="text-base leading-4 rounded-lg hover:text-blue-1"
                    @endif
                    >
                    {{ $category->title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>