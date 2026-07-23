@props(['title' => 'Accesso rapido', 'links' => []])

{{--
    Quick Links Block
    
    Usage:
    "view": "pub_theme::components.blocks.quick-links.default",
    "title": "Accesso rapido",
    "links": [
        {
            "icon": "it-calendar",
            "label": "Appuntamenti",
            "url": "/it/appuntamenti"
        }
    ]
    
    Docs: docs/design-comuni/blocks/00-index.md
--}}

<section class="quick-links-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">{{ $title }}</h2>
                
                <div class="quick-links-grid">
                    @foreach($links as $link)
                        <a href="{{ $link['url'] }}" class="quick-link-card">
                            <div class="quick-link-icon">
                                <svg class="icon">
                                    <use href="#{{ $link['icon'] }}"></use>
                                </svg>
                            </div>
                            <span class="quick-link-label">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
