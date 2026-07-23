// Tema Sixteen - Funzioni JavaScript per Design Comuni

document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inizializza popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Smooth scroll per i link interni
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Form validation personalizzata
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Lazy loading per le immagini
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Gestione del menu mobile
    const navbarToggler = document.querySelector('.custom-navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
        
        // Chiudi il menu quando si clicca su un link
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                navbarCollapse.classList.remove('show');
            });
        });
    }
    
    // Gestione dei filtri per le novità
    const filterButtons = document.querySelectorAll('.list-group-item[data-filter]');
    const newsItems = document.querySelectorAll('.news-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Rimuovi classe active da tutti i pulsanti
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Aggiungi classe active al pulsante cliccato
            this.classList.add('active');
            
            // Filtra le notizie
            const filter = this.dataset.filter;
            
            newsItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Gestione del form di contatto
    const contactForm = document.querySelector('#contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostra messaggio di successo
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success';
            successMessage.textContent = 'Messaggio inviato con successo!';
            
            contactForm.parentNode.insertBefore(successMessage, contactForm);
            contactForm.reset();
            
            // Rimuovi il messaggio dopo 5 secondi
            setTimeout(() => {
                successMessage.remove();
            }, 5000);
        });
    }
    
    // Gestione della mappa
    if (typeof L !== 'undefined') {
        // Inizializza mappa se presente
        const mapElement = document.getElementById('map');
        if (mapElement) {
            const lat = parseFloat(mapElement.dataset.lat) || 45.4642;
            const lng = parseFloat(mapElement.dataset.lng) || 9.1900;
            const zoom = parseInt(mapElement.dataset.zoom) || 15;
            
            const map = L.map('map').setView([lat, lng], zoom);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([lat, lng]).addTo(map)
                .bindPopup('Comune di {{ config("comune.nome", "Nome Comune") }}')
                .openPopup();
        }
    }
    
    // Gestione delle card hover
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Gestione del scroll per header fisso
    let lastScrollTop = 0;
    const header = document.querySelector('.it-header-wrapper');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Gestione del tema scuro (se implementato)
    const themeToggle = document.querySelector('#theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            localStorage.setItem('darkTheme', document.body.classList.contains('dark-theme'));
        });
        
        // Carica tema salvato
        if (localStorage.getItem('darkTheme') === 'true') {
            document.body.classList.add('dark-theme');
        }
    }
    
    // Gestione delle animazioni al scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Osserva elementi da animare
    document.querySelectorAll('.card, .it-page-section').forEach(el => {
        observer.observe(el);
    });
});


