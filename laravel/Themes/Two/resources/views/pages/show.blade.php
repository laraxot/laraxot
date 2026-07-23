<div>
    <x-banner>
        <h1>{{ $page->title }}</h1>
    </x-banner>

    <x-std tpl='container'>
        <div class="prose mt-8 mx-auto text-black">
            {!! $page->content !!}
        </div>
    </x-std>
</div>
