<article class="bg-white flex flex-col shadow my-4">
    <!-- Article Image -->
    <a href="{{ $this->url('show', ['record' => $article]) }}" class="hover:opacity-75">
        <img src="{{$article->getMainImage()}}" alt="{{$article->getTitle()}}" class="aspect-[4/3] object-contain">
    </a>
    <div class="bg-white flex flex-col justify-start p-6">
        <div class="flex gap-4">
            @foreach($article->categories as $category)
                <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4">
                    {{$category->title}}
                </a>
            @endforeach
        </div>
        <a href="{{ $this->url('show', ['record' => $article]) }}" class="text-3xl font-bold hover:text-gray-700 pb-4">
            {{$article->getTitle()}}
        </a>
        @if ($showAuthor)
            <p href="#" class="text-sm pb-3">
                By <a href="#" class="font-semibold hover:text-gray-800">{{$article->user->name}}</a>, Published on
                {{$article->getFormattedDate()}} | {{ $article->human_read_time }}
            </p>
        @endif
        <a href="{{ $this->url('show', ['record' => $article]) }}" class="pb-6">
            {{$article->shortBody()}}
        </a>
        <a  href="{{ $this->url('show', ['record' => $article]) }}" class="uppercase text-gray-800 hover:text-black">Continue Reading <i
                class="fas fa-arrow-right"></i></a>
    </div>
</article>
