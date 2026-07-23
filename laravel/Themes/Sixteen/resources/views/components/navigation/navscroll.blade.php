{{--
/**
 * Navscroll Component - Bootstrap Italia Compliant
 * 
 * Vertical navigation that highlights sections as user scrolls through page
 * Typically used for long-form content with multiple sections
 * 
 * @param string $id Unique ID for the navscroll
 * @param array $sections Array of page sections with IDs and labels
 * @param string $position Position: 'left' or 'right'
 * @param int $offset Scroll offset in pixels
 * @param bool $sticky Whether to use sticky positioning
 * @param string $theme Theme variant: 'light' or 'dark'
 */
--}}

@props([
    'id' => 'navscroll-' . uniqid(),
    'sections' => [],
    'position' => 'right',
    'offset' => 100,
    'sticky' => true,
    'theme' => 'light',
])

@php
    $positionClass = $position === 'left' ? 'left-0' : 'right-0';
    $themeClass = $theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-gray-800 border';
@endphp

<nav 
    id="{{ $id }}"
    class="navscroll {{ $positionClass }} {{ $themeClass }} {{ $sticky ? 'sticky top-20' : '' }} z-40 w-64 p-4 rounded-lg shadow-lg"
    x-data="{
        activeSection: null,
        sections: @js($sections),
        
        init() {
            this.setupIntersectionObserver();
            window.addEventListener('scroll', this.debounce(this.updateActiveSection, 100));
        },
        
        setupIntersectionObserver() {
            this.sections.forEach(section => {
                const element = document.getElementById(section.id);
                if (element) {
                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    this.activeSection = section.id;
                                }
                            });
                        },
                        { threshold: 0.5, rootMargin: `-${this.offset}px 0px -${window.innerHeight - this.offset - 100}px 0px` }
                    );
                    observer.observe(element);
                }
            });
        },
        
        updateActiveSection() {
            const scrollPosition = window.scrollY + this.offset;
            
            this.sections.forEach(section => {
                const element = document.getElementById(section.id);
                if (element) {
                    const rect = element.getBoundingClientRect();
                    const elementTop = rect.top + window.scrollY;
                    const elementBottom = elementTop + rect.height;
                    
                    if (scrollPosition >= elementTop && scrollPosition < elementBottom) {
                        this.activeSection = section.id;
                    }
                }
            });
        },
        
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                const offsetPosition = element.offsetTop - {{ $offset }};
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
    }"
>
    <div class="space-y-2">
        <h3 class="font-semibold text-lg mb-3 border-b pb-2">Navigazione Pagina</h3>
        
        @foreach($sections as $section)
            <button
                type="button"
                class="w-full text-left px-3 py-2 rounded transition-colors duration-200"
                :class="{
                    'bg-blue-600 text-white': activeSection === '{{ $section['id'] }}',
                    'hover:bg-gray-100 hover:text-gray-900': activeSection !== '{{ $section['id'] }}' && theme === 'light',
                    'hover:bg-gray-700 hover:text-white': activeSection !== '{{ $section['id'] }}' && theme === 'dark'
                }"
                @click="scrollToSection('{{ $section['id'] }}')"
            >
                {{ $section['label'] }}
            </button>
        @endforeach
    </div>
</nav>