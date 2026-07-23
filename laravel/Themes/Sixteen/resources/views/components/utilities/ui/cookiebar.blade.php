{{-- 
/**
 * Cookiebar Component - Bootstrap Italia Compliant
 * 
 * GDPR-compliant cookie banner for Italian PA websites
 * Essential for legal compliance with privacy regulations
 * 
 * @param string $message Cookie usage message (default provided)
 * @param string $acceptText Accept button text (default: 'Accetto')
 * @param string $preferencesText Preferences link text (default: 'Preferenze')
 * @param string $preferencesUrl URL for cookie preferences page (default: '#')
 * @param string $ariaLabel Accessible label (default: 'Gestione dei cookies')
 * @param bool $show Whether to show the cookiebar (can be controlled by JavaScript)
 */
--}}

@props([
    'message' => 'Questo sito utilizza cookie tecnici, analytics e di terze parti. Proseguendo nella navigazione accetti l\'utilizzo dei cookie.',
    'acceptText' => 'Accetto',
    'preferencesText' => 'Preferenze',
    'preferencesUrl' => '#',
    'ariaLabel' => 'Gestione dei cookies',
    'show' => true
])

@if($show)
<section class="cookiebar fade" aria-label="{{ $ariaLabel }}">
    <p>{!! $message !!}</p>
    <div class="cookiebar-buttons">
        <a href="{{ $preferencesUrl }}" class="cookiebar-btn">
            {{ $preferencesText }}
            <span class="visually-hidden">cookies</span>
        </a>
        <button data-bs-accept="cookiebar" class="cookiebar-btn cookiebar-confirm">
            {{ $acceptText }}
            <span class="visually-hidden"> i cookies</span>
        </button>
    </div>
</section>

{{-- Include JavaScript for cookiebar functionality --}}
@pushOnce('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cookiebar functionality
    const cookiebar = document.querySelector('.cookiebar');
    const acceptBtn = document.querySelector('[data-bs-accept="cookiebar"]');
    
    if (cookiebar && acceptBtn) {
        // Check if cookies have been accepted
        if (localStorage.getItem('cookies-accepted') === 'true') {
            cookiebar.style.display = 'none';
            return;
        }
        
        // Show cookiebar with fade in effect
        setTimeout(() => {
            cookiebar.classList.add('show');
        }, 100);
        
        // Handle accept button click
        acceptBtn.addEventListener('click', function() {
            localStorage.setItem('cookies-accepted', 'true');
            cookiebar.classList.remove('show');
            
            // Hide after transition
            setTimeout(() => {
                cookiebar.style.display = 'none';
            }, 300);
            
            // Fire custom event for analytics/tracking
            window.dispatchEvent(new CustomEvent('cookiesAccepted'));
        });
    }
});
</script>
@endPushOnce

{{-- Include CSS for cookiebar styling --}}
@pushOnce('styles')
<style>
.cookiebar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background-color: #f0f6fc;
    border-top: 1px solid #d9e3f1;
    padding: 1rem;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    transform: translateY(100%);
    transition: transform 0.3s ease-in-out;
}

.cookiebar.show {
    transform: translateY(0);
}

.cookiebar p {
    margin: 0 0 1rem 0;
    color: #17324d;
    font-size: 0.875rem;
    line-height: 1.4;
}

.cookiebar-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.cookiebar-btn {
    background-color: transparent;
    border: 1px solid #0066cc;
    color: #0066cc;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
}

.cookiebar-btn:hover,
.cookiebar-btn:focus {
    background-color: #0066cc;
    color: white;
    text-decoration: none;
}

.cookiebar-confirm {
    background-color: #0066cc;
    color: white;
}

.cookiebar-confirm:hover,
.cookiebar-confirm:focus {
    background-color: #004499;
}

@media (max-width: 768px) {
    .cookiebar {
        padding: 0.75rem;
    }
    
    .cookiebar-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .cookiebar-btn {
        text-align: center;
        justify-content: center;
    }
}
</style>
@endPushOnce
@endif

{{-- 
Usage Examples:

1. Basic cookiebar (default):
<x-pub_theme::cookiebar />

2. Custom message and preferences URL:
<x-pub_theme::cookiebar 
    message="Questo sito web utilizza cookies per migliorare l'esperienza utente.<br />Continuando la navigazione si acconsente all'uso dei cookies."
    preferences-url="/cookie-preferences" />

3. Custom button text:
<x-pub_theme::cookiebar 
    accept-text="Acconsento"
    preferences-text="Gestisci preferenze" />

4. Conditionally show:
<x-pub_theme::cookiebar :show="!session('cookies_accepted')" />

Integration with Layout:
Place this component just after the opening <body> tag in your main layout:

@include('pub_theme::components.cookiebar')
--}}
