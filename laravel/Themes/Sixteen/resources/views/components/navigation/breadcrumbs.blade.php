@props([
    'links' => [],
    'current' => '',
    'ariaLabel' => 'breadcrumb',
    'divider' => '/',
])

<nav aria-label="{{ $ariaLabel }}">
    <ol class="breadcrumb">
        @foreach($links as $link)
            <li class="breadcrumb-item">
                <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            </li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">
            {{ $current }}
        </li>
    </ol>
</nav>
