@props(['items' => []])

{{--
    Navigation Block - Main Menu
    
    Usage:
    "view": "pub_theme::components.blocks.navigation.main",
    "items": [
        {
            "label": "Amministrazione",
            "url": "/it/amministrazione",
            "children": [...]
        }
    ]
    
    Docs: docs/design-comuni/blocks/00-index.md
--}}

<nav class="it-nav-wrapper" aria-label="Navigazione principale">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-nav-list">
                    <ul class="link-list">
                        @foreach($items as $item)
                            <li>
                                <a href="{{ $item['url'] }}">
                                    <span>{{ $item['label'] }}</span>
                                </a>
                                @if(isset($item['children']) && count($item['children']) > 0)
                                    <ul class="link-sublist">
                                        @foreach($item['children'] as $child)
                                            <li>
                                                <a href="{{ $child['url'] }}">
                                                    <span>{{ $child['label'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
