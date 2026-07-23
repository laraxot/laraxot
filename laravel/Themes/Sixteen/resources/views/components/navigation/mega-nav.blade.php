{{--
Componente: Mega Navigation
Descrizione: Navigazione mega menu avanzata per siti complessi della PA
Documentazione: https://designers.italia.it/modello/comuni/
--}}

@props([
    'title' => 'Navigazione',
    'items' => [],
    'variant' => 'primary',
    'alignment' => 'center',
    'fullWidth' => false,
])

@php
    $baseClasses = 'bi-mega-nav';
    
    $variantClasses = match($variant) {
        'primary' => 'bi-mega-nav-primary',
        'secondary' => 'bi-mega-nav-secondary',
        'institutional' => 'bi-mega-nav-institutional',
        default => 'bi-mega-nav-primary'
    };
    
    $alignmentClasses = match($alignment) {
        'left' => 'bi-mega-nav-left',
        'center' => 'bi-mega-nav-center',
        'right' => 'bi-mega-nav-right',
        default => 'bi-mega-nav-center'
    };
    
    $widthClasses = $fullWidth ? 'bi-mega-nav-fullwidth' : 'bi-mega-nav-contained';
    
    $classes = implode(' ', [$baseClasses, $variantClasses, $alignmentClasses, $widthClasses]);
@endphp

<nav {{ $attributes->merge(['class' => $classes, 'aria-label' => 'Mega navigation']) }}>
    <div class="bi-mega-nav-container">
        @if($title)
            <h2 class="bi-mega-nav-title">
                {{ $title }}
            </h2>
        @endif
        
        <div class="bi-mega-nav-content">
            @foreach($items as $column)
                <div class="bi-mega-nav-column">
                    @isset($column['title'])
                        <h3 class="bi-mega-nav-column-title">
                            {{ $column['title'] }}
                        </h3>
                    @endisset
                    
                    @isset($column['items'])
                        <ul class="bi-mega-nav-list">
                            @foreach($column['items'] as $item)
                                <li class="bi-mega-nav-item">
                                    <a 
                                        href="{{ $item['url'] ?? '#' }}"
                                        class="bi-mega-nav-link"
                                        @isset($item['description']) aria-describedby="desc-{{ Str::slug($item['title']) }}" @endisset
                                    >
                                        @isset($item['icon'])
                                            <span class="bi-mega-nav-icon">
                                                {!! $item['icon'] !!}
                                            </span>
                                        @endisset
                                        
                                        <span class="bi-mega-nav-text">
                                            {{ $item['title'] }}
                                        </span>
                                        
                                        @isset($item['badge'])
                                            <span class="bi-mega-nav-badge">
                                                {{ $item['badge'] }}
                                            </span>
                                        @endisset
                                    </a>
                                    
                                    @isset($item['description'])
                                        <div id="desc-{{ Str::slug($item['title']) }}" class="bi-mega-nav-description">
                                            {{ $item['description'] }}
                                        </div>
                                    @endisset
                                </li>
                            @endforeach
                        </ul>
                    @endisset
                </div>
            @endforeach
        </div>
        
        <div class="bi-mega-nav-footer">
            <button type="button" class="bi-mega-nav-close" aria-label="Chiudi mega menu">
                <svg class="bi-mega-nav-close-icon" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

@push('styles')
<style>
.bi-mega-nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    display: none;
    overflow-y: auto;
}

.bi-mega-nav-container {
    background: white;
    max-width: 1200px;
    margin: 0 auto;
    height: 100%;
    position: relative;
}

.bi-mega-nav-fullwidth .bi-mega-nav-container {
    max-width: 100%;
}

.bi-mega-nav-title {
    font-size: 1.5rem;
    font-weight: 600;
    padding: 2rem 2rem 1rem;
    margin: 0;
    color: var(--italia-blue-900);
}

.bi-mega-nav-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    padding: 0 2rem 2rem;
}

.bi-mega-nav-column-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    color: var(--italia-blue-800);
}

.bi-mega-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.bi-mega-nav-item {
    margin-bottom: 0.75rem;
}

.bi-mega-nav-link {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    color: var(--italia-blue-700);
    text-decoration: none;
    transition: color 0.2s ease;
}

.bi-mega-nav-link:hover {
    color: var(--italia-blue-500);
}

.bi-mega-nav-icon {
    margin-right: 0.75rem;
    width: 1.25rem;
    height: 1.25rem;
}

.bi-mega-nav-text {
    flex: 1;
}

.bi-mega-nav-badge {
    background: var(--italia-green-100);
    color: var(--italia-green-800);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.bi-mega-nav-description {
    font-size: 0.875rem;
    color: var(--italia-gray-600);
    margin-top: 0.25rem;
    line-height: 1.4;
}

.bi-mega-nav-footer {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.bi-mega-nav-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: background-color 0.2s ease;
}

.bi-mega-nav-close:hover {
    background: var(--italia-gray-100);
}

.bi-mega-nav-close-icon {
    width: 1.5rem;
    height: 1.5rem;
}

/* Varianti */
.bi-mega-nav-primary {
    background: rgba(var(--italia-blue-500-rgb), 0.9);
}

.bi-mega-nav-secondary {
    background: rgba(var(--italia-gray-500-rgb), 0.9);
}

.bi-mega-nav-institutional {
    background: rgba(var(--italia-blue-800-rgb), 0.9);
}

/* Allineamenti */
.bi-mega-nav-left .bi-mega-nav-container {
    margin-left: 0;
    margin-right: auto;
}

.bi-mega-nav-right .bi-mega-nav-container {
    margin-left: auto;
    margin-right: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .bi-mega-nav-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem 2rem;
    }
    
    .bi-mega-nav-title {
        padding: 1.5rem 1rem 1rem;
        font-size: 1.25rem;
    }
}

/* Accessibilità */
.bi-mega-nav:focus {
    outline: 2px solid var(--italia-blue-500);
    outline-offset: 2px;
}

.bi-mega-nav-link:focus {
    outline: 2px solid var(--italia-blue-300);
    outline-offset: 2px;
}

/* Animazioni */
.bi-mega-nav {
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.bi-mega-nav.show {
    opacity: 1;
    transform: translateY(0);
    display: block;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .bi-mega-nav {
        transition: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
class MegaNav {
    constructor(element) {
        this.element = element;
        this.closeButton = element.querySelector('.bi-mega-nav-close');
        this.init();
    }
    
    init() {
        this.closeButton.addEventListener('click', () => this.hide());
        
        // Keyboard navigation
        this.element.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.hide();
            if (e.key === 'Tab') this.handleTabNavigation(e);
        });
        
        // Trap focus inside modal
        this.element.addEventListener('focusin', () => {
            this.trapFocus();
        });
    }
    
    show() {
        this.element.classList.add('show');
        this.element.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        
        // Focus first focusable element
        setTimeout(() => {
            const firstFocusable = this.element.querySelector('a, button, input');
            if (firstFocusable) firstFocusable.focus();
        }, 100);
    }
    
    hide() {
        this.element.classList.remove('show');
        this.element.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    
    handleTabNavigation(e) {
        const focusableElements = this.getFocusableElements();
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }
    
    getFocusableElements() {
        return Array.from(this.element.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"]'
        ));
    }
    
    trapFocus() {
        const focusableElements = this.getFocusableElements();
        if (focusableElements.length === 0) return;
        
        const activeElement = document.activeElement;
        if (!this.element.contains(activeElement)) {
            focusableElements[0].focus();
        }
    }
}

// Auto-initialize
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.bi-mega-nav').forEach(element => {
        new MegaNav(element);
    });
});

// Global function to show mega nav
window.showMegaNav = (id) => {
    const element = document.getElementById(id);
    if (element) {
        new MegaNav(element).show();
    }
};
</script>
@endpush