// AGID Color Enforcer - Forza l'applicazione dei colori AGID
document.addEventListener('DOMContentLoaded', function() {
    // Colori AGID
    const agidColors = {
        blue: '#0066CC',
        green: '#00B373',
        red: '#D9364F',
        yellow: '#F5A623'
    };

    // Funzione per applicare colori AGID
    function applyAgidColors() {
        // Override per header principale
        const headers = document.querySelectorAll('.header-main, .it-header-wrapper, .it-header-center-wrapper');
        headers.forEach(header => {
            header.style.backgroundColor = agidColors.blue;
        });

        // Override per bottoni primari
        const primaryButtons = document.querySelectorAll('.btn-primary, .filament-button-primary, [data-filament-button="primary"]');
        primaryButtons.forEach(button => {
            button.style.backgroundColor = agidColors.blue;
            button.style.borderColor = agidColors.blue;
        });

        // Override per input focus
        const inputs = document.querySelectorAll('input, select, textarea, .filament-input, .filament-select, .filament-textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = agidColors.blue;
                this.style.boxShadow = `0 0 0 3px rgba(0, 102, 204, 0.1)`;
            });
        });

        // Override per link
        const links = document.querySelectorAll('a, .filament-navigation-item, .nav-link');
        links.forEach(link => {
            link.style.color = agidColors.blue;
        });

        // Override per badge
        const badges = document.querySelectorAll('.badge-primary, .filament-badge-primary');
        badges.forEach(badge => {
            badge.style.backgroundColor = '#e6f2ff';
            badge.style.color = '#001429';
        });

        // Override per alert
        const alerts = document.querySelectorAll('.alert-success, .alert-danger, .alert-warning, .alert-info');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-success')) {
                alert.style.backgroundColor = '#e6f7f0';
                alert.style.borderColor = '#80d5b2';
                alert.style.color = '#002317';
            } else if (alert.classList.contains('alert-danger')) {
                alert.style.backgroundColor = '#fce8ea';
                alert.style.borderColor = '#f27e86';
                alert.style.color = '#2d0a0f';
            } else if (alert.classList.contains('alert-warning')) {
                alert.style.backgroundColor = '#fff8e6';
                alert.style.borderColor = '#ffe080';
                alert.style.color = '#312107';
            } else if (alert.classList.contains('alert-info')) {
                alert.style.backgroundColor = '#e6f2ff';
                alert.style.borderColor = '#80bfff';
                alert.style.color = '#001429';
            }
        });
    }

    // Applica i colori immediatamente
    applyAgidColors();

    // Osserva i cambiamenti nel DOM per applicare i colori ai nuovi elementi
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                applyAgidColors();
            }
        });
    });

    // Inizia l'osservazione
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Applica i colori anche quando la pagina è completamente caricata
    window.addEventListener('load', applyAgidColors);
});
