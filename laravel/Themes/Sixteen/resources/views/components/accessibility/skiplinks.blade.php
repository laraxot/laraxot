{{--
/**
 * Skip Links Component
 * 
 * Componente per i link di salto per la navigazione da tastiera.
 * Conforme alle linee guida WCAG 2.1 AA e AGID.
 * 
 * @package Themes\Sixteen
 * @author Marco Xot <marco.sottana@gmail.com>
 * @version 1.0.0
 */
--}}

@props([
    'links' => [
        'main' => 'Vai al contenuto principale',
        'navigation' => 'Vai alla navigazione',
        'search' => 'Vai alla ricerca',
        'footer' => 'Vai al footer'
    ]
])

<div class="skip-links" data-skip-links>
    @foreach($links as $target => $label)
        <a 
            href="#{{ $target }}"
            class="skip-link"
            data-skip-target="{{ $target }}"
        >
            {{ $label }}
        </a>
    @endforeach
</div>

@push('styles')
<style>
/* Stili per skip links */
.skip-links {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 9999;
}

.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: #000;
    color: #fff;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    line-height: 1.4;
    transition: top 0.3s ease;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.skip-link:focus {
    top: 6px;
    outline: 2px solid #fff;
    outline-offset: 2px;
}

.skip-link:hover {
    background: #333;
    color: #fff;
}

/* Nascondi skip links quando non sono in focus */
.skip-link:not(:focus) {
    clip: rect(0 0 0 0);
    clip-path: inset(50%);
    height: 1px;
    overflow: hidden;
    position: absolute;
    white-space: nowrap;
    width: 1px;
}

/* Stili per alto contrasto */
.high-contrast .skip-link {
    background: #000 !important;
    color: #fff !important;
    border: 2px solid #fff !important;
}

.high-contrast .skip-link:focus {
    background: #fff !important;
    color: #000 !important;
    outline: 3px solid #000 !important;
}

/* Responsive */
@media (max-width: 640px) {
    .skip-link {
        left: 4px;
        right: 4px;
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skipLinks = document.querySelectorAll('[data-skip-target]');
    
    skipLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('data-skip-target');
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                // Focus sull'elemento target
                targetElement.focus();
                
                // Scroll smooth all'elemento
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Aggiungi classe per evidenziare l'elemento
                targetElement.classList.add('skip-target-highlight');
                
                // Rimuovi la classe dopo 3 secondi
                setTimeout(() => {
                    targetElement.classList.remove('skip-target-highlight');
                }, 3000);
            }
        });
    });
    
    // Gestione focus per elementi che non sono naturalmente focusabili
    skipLinks.forEach(link => {
        link.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Evidenziazione elemento target */
.skip-target-highlight {
    outline: 3px solid #0066cc !important;
    outline-offset: 2px !important;
    background-color: rgba(0, 102, 204, 0.1) !important;
    transition: all 0.3s ease !important;
}

/* Stili per elementi che ricevono focus */
.skip-target-highlight:focus {
    outline: 3px solid #0066cc !important;
    outline-offset: 2px !important;
}

/* Stili per alto contrasto */
.high-contrast .skip-target-highlight {
    outline: 3px solid #ffff00 !important;
    background-color: rgba(255, 255, 0, 0.2) !important;
}
</style>
@endpush
