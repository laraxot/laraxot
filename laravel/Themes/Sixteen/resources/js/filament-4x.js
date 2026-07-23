/**
 * Filament 4.x Integration - Tema Sixteen
 * 
 * Questo file contiene le funzionalità JavaScript specifiche per l'integrazione
 * con Filament 4.x nel tema Sixteen, seguendo le best practice ufficiali.
 */

// ===========================================
// FILAMENT 4.X INTEGRATION
// ===========================================

/**
 * Inizializzazione componenti Filament 4.x
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeFilamentComponents();
    initializeFilamentForms();
    initializeFilamentTables();
    initializeFilamentModals();
    initializeFilamentNotifications();
});

/**
 * Inizializza i componenti base di Filament
 */
function initializeFilamentComponents() {
    // Inizializza tooltip per elementi Filament
    initializeFilamentTooltips();
    
    // Inizializza dropdown per elementi Filament
    initializeFilamentDropdowns();
    
    // Inizializza accordion per elementi Filament
    initializeFilamentAccordions();
}

/**
 * Inizializza i tooltip per i componenti Filament
 */
function initializeFilamentTooltips() {
    const tooltipElements = document.querySelectorAll('[data-filament-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            showFilamentTooltip(this);
        });
        
        element.addEventListener('mouseleave', function() {
            hideFilamentTooltip(this);
        });
    });
}

/**
 * Mostra tooltip per elemento Filament
 */
function showFilamentTooltip(element) {
    const tooltipText = element.getAttribute('data-filament-tooltip');
    if (!tooltipText) return;
    
    const tooltip = document.createElement('div');
    tooltip.className = 'filament-tooltip';
    tooltip.textContent = tooltipText;
    tooltip.style.cssText = `
        position: absolute;
        background: #1f2937;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
    `;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    setTimeout(() => {
        tooltip.style.opacity = '1';
    }, 10);
    
    element._filamentTooltip = tooltip;
}

/**
 * Nasconde tooltip per elemento Filament
 */
function hideFilamentTooltip(element) {
    if (element._filamentTooltip) {
        element._filamentTooltip.style.opacity = '0';
        setTimeout(() => {
            if (element._filamentTooltip && element._filamentTooltip.parentNode) {
                element._filamentTooltip.parentNode.removeChild(element._filamentTooltip);
            }
            element._filamentTooltip = null;
        }, 200);
    }
}

/**
 * Inizializza i dropdown per i componenti Filament
 */
function initializeFilamentDropdowns() {
    const dropdownToggles = document.querySelectorAll('[data-filament-dropdown-toggle]');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = document.querySelector(this.getAttribute('data-filament-dropdown-toggle'));
            if (dropdown) {
                toggleFilamentDropdown(dropdown);
            }
        });
    });
    
    // Chiudi dropdown quando si clicca fuori
    document.addEventListener('click', function(e) {
        const openDropdowns = document.querySelectorAll('.filament-dropdown[data-filament-dropdown-open="true"]');
        openDropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                closeFilamentDropdown(dropdown);
            }
        });
    });
}

/**
 * Toggle dropdown Filament
 */
function toggleFilamentDropdown(dropdown) {
    const isOpen = dropdown.getAttribute('data-filament-dropdown-open') === 'true';
    
    if (isOpen) {
        closeFilamentDropdown(dropdown);
    } else {
        openFilamentDropdown(dropdown);
    }
}

/**
 * Apri dropdown Filament
 */
function openFilamentDropdown(dropdown) {
    dropdown.setAttribute('data-filament-dropdown-open', 'true');
    dropdown.style.display = 'block';
    dropdown.style.opacity = '0';
    dropdown.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
        dropdown.style.opacity = '1';
        dropdown.style.transform = 'translateY(0)';
    }, 10);
}

/**
 * Chiudi dropdown Filament
 */
function closeFilamentDropdown(dropdown) {
    dropdown.style.opacity = '0';
    dropdown.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
        dropdown.setAttribute('data-filament-dropdown-open', 'false');
        dropdown.style.display = 'none';
    }, 200);
}

/**
 * Inizializza gli accordion per i componenti Filament
 */
function initializeFilamentAccordions() {
    const accordionToggles = document.querySelectorAll('[data-filament-accordion-toggle]');
    
    accordionToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const accordion = document.querySelector(this.getAttribute('data-filament-accordion-toggle'));
            if (accordion) {
                toggleFilamentAccordion(accordion);
            }
        });
    });
}

/**
 * Toggle accordion Filament
 */
function toggleFilamentAccordion(accordion) {
    const isOpen = accordion.getAttribute('data-filament-accordion-open') === 'true';
    const content = accordion.querySelector('.filament-accordion-content');
    
    if (isOpen) {
        closeFilamentAccordion(accordion, content);
    } else {
        openFilamentAccordion(accordion, content);
    }
}

/**
 * Apri accordion Filament
 */
function openFilamentAccordion(accordion, content) {
    accordion.setAttribute('data-filament-accordion-open', 'true');
    content.style.maxHeight = content.scrollHeight + 'px';
    accordion.classList.add('filament-accordion-open');
}

/**
 * Chiudi accordion Filament
 */
function closeFilamentAccordion(accordion, content) {
    accordion.setAttribute('data-filament-accordion-open', 'false');
    content.style.maxHeight = '0';
    accordion.classList.remove('filament-accordion-open');
}

/**
 * Inizializza i form di Filament
 */
function initializeFilamentForms() {
    // Inizializza validazione form
    initializeFilamentFormValidation();
    
    // Inizializza upload file
    initializeFilamentFileUpload();
    
    // Inizializza select multipli
    initializeFilamentMultiSelect();
}

/**
 * Inizializza validazione form Filament
 */
function initializeFilamentFormValidation() {
    const forms = document.querySelectorAll('form[data-filament-form]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateFilamentForm(this)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Valida form Filament
 */
function validateFilamentForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFilamentFieldError(field, 'Questo campo è obbligatorio');
            isValid = false;
        } else {
            clearFilamentFieldError(field);
        }
    });
    
    return isValid;
}

/**
 * Mostra errore per campo Filament
 */
function showFilamentFieldError(field, message) {
    clearFilamentFieldError(field);
    
    const errorElement = document.createElement('div');
    errorElement.className = 'filament-field-error';
    errorElement.textContent = message;
    errorElement.style.cssText = 'color: #ef4444; font-size: 14px; margin-top: 4px;';
    
    field.parentNode.appendChild(errorElement);
    field.classList.add('filament-input-error');
}

/**
 * Rimuovi errore per campo Filament
 */
function clearFilamentFieldError(field) {
    const existingError = field.parentNode.querySelector('.filament-field-error');
    if (existingError) {
        existingError.remove();
    }
    field.classList.remove('filament-input-error');
}

/**
 * Inizializza upload file Filament
 */
function initializeFilamentFileUpload() {
    const fileInputs = document.querySelectorAll('input[type="file"][data-filament-file-upload]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleFilamentFileUpload(this);
        });
    });
}

/**
 * Gestisce upload file Filament
 */
function handleFilamentFileUpload(input) {
    const files = input.files;
    const preview = input.parentNode.querySelector('.filament-file-preview');
    
    if (preview) {
        preview.innerHTML = '';
        
        Array.from(files).forEach(file => {
            const fileElement = document.createElement('div');
            fileElement.className = 'filament-file-item';
            fileElement.innerHTML = `
                <span class="filament-file-name">${file.name}</span>
                <span class="filament-file-size">${formatFileSize(file.size)}</span>
            `;
            preview.appendChild(fileElement);
        });
    }
}

/**
 * Formatta dimensione file
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Inizializza select multipli Filament
 */
function initializeFilamentMultiSelect() {
    const multiSelects = document.querySelectorAll('[data-filament-multi-select]');
    
    multiSelects.forEach(select => {
        select.addEventListener('change', function() {
            updateFilamentMultiSelectDisplay(this);
        });
        
        updateFilamentMultiSelectDisplay(select);
    });
}

/**
 * Aggiorna display select multiplo Filament
 */
function updateFilamentMultiSelectDisplay(select) {
    const selectedOptions = Array.from(select.selectedOptions);
    const display = select.parentNode.querySelector('.filament-multi-select-display');
    
    if (display) {
        if (selectedOptions.length === 0) {
            display.textContent = 'Nessuna opzione selezionata';
        } else if (selectedOptions.length === 1) {
            display.textContent = selectedOptions[0].textContent;
        } else {
            display.textContent = `${selectedOptions.length} opzioni selezionate`;
        }
    }
}

/**
 * Inizializza le tabelle di Filament
 */
function initializeFilamentTables() {
    // Inizializza ordinamento colonne
    initializeFilamentTableSorting();
    
    // Inizializza filtri
    initializeFilamentTableFilters();
    
    // Inizializza paginazione
    initializeFilamentTablePagination();
}

/**
 * Inizializza ordinamento colonne tabella Filament
 */
function initializeFilamentTableSorting() {
    const sortableHeaders = document.querySelectorAll('[data-filament-table-sortable]');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            sortFilamentTable(this);
        });
    });
}

/**
 * Ordina tabella Filament
 */
function sortFilamentTable(header) {
    const table = header.closest('table');
    const column = header.getAttribute('data-filament-table-sortable');
    const currentSort = header.getAttribute('data-filament-table-sort');
    
    // Rimuovi indicatori di ordinamento da tutte le colonne
    table.querySelectorAll('[data-filament-table-sortable]').forEach(h => {
        h.removeAttribute('data-filament-table-sort');
        h.classList.remove('filament-table-sort-asc', 'filament-table-sort-desc');
    });
    
    // Imposta nuovo ordinamento
    let newSort = 'asc';
    if (currentSort === 'asc') {
        newSort = 'desc';
    }
    
    header.setAttribute('data-filament-table-sort', newSort);
    header.classList.add(`filament-table-sort-${newSort}`);
    
    // Qui potresti implementare la logica di ordinamento effettiva
    // o inviare una richiesta al server per ordinare i dati
}

/**
 * Inizializza filtri tabella Filament
 */
function initializeFilamentTableFilters() {
    const filterInputs = document.querySelectorAll('[data-filament-table-filter]');
    
    filterInputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            applyFilamentTableFilter(input);
        }, 300));
    });
}

/**
 * Applica filtro tabella Filament
 */
function applyFilamentTableFilter(input) {
    const filterValue = input.value.toLowerCase();
    const table = input.closest('[data-filament-table]');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Inizializza paginazione tabella Filament
 */
function initializeFilamentTablePagination() {
    const paginationLinks = document.querySelectorAll('[data-filament-table-page]');
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadFilamentTablePage(this);
        });
    });
}

/**
 * Carica pagina tabella Filament
 */
function loadFilamentTablePage(link) {
    const page = link.getAttribute('data-filament-table-page');
    const table = link.closest('[data-filament-table]');
    
    // Qui potresti implementare la logica di caricamento della pagina
    // o inviare una richiesta al server per caricare i dati della pagina
    console.log(`Caricamento pagina ${page} per tabella`, table);
}

/**
 * Inizializza i modal di Filament
 */
function initializeFilamentModals() {
    const modalTriggers = document.querySelectorAll('[data-filament-modal-trigger]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            openFilamentModal(this);
        });
    });
    
    // Chiudi modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllFilamentModals();
        }
    });
}

/**
 * Apri modal Filament
 */
function openFilamentModal(trigger) {
    const modalId = trigger.getAttribute('data-filament-modal-trigger');
    const modal = document.getElementById(modalId);
    
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('filament-modal-open');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Chiudi modal Filament
 */
function closeFilamentModal(modal) {
    modal.style.display = 'none';
    modal.classList.remove('filament-modal-open');
    document.body.style.overflow = '';
}

/**
 * Chiudi tutti i modal Filament
 */
function closeAllFilamentModals() {
    const openModals = document.querySelectorAll('.filament-modal-open');
    openModals.forEach(modal => {
        closeFilamentModal(modal);
    });
}

/**
 * Inizializza le notifiche di Filament
 */
function initializeFilamentNotifications() {
    // Mostra notifiche esistenti
    showExistingFilamentNotifications();
    
    // Inizializza chiusura notifiche
    initializeFilamentNotificationClose();
}

/**
 * Mostra notifiche esistenti Filament
 */
function showExistingFilamentNotifications() {
    const notifications = document.querySelectorAll('.filament-notification[data-filament-notification-auto-show="true"]');
    
    notifications.forEach(notification => {
        showFilamentNotification(notification);
    });
}

/**
 * Mostra notifica Filament
 */
function showFilamentNotification(notification) {
    notification.style.display = 'block';
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto-hide se specificato
    const autoHide = notification.getAttribute('data-filament-notification-auto-hide');
    if (autoHide) {
        setTimeout(() => {
            hideFilamentNotification(notification);
        }, parseInt(autoHide) * 1000);
    }
}

/**
 * Nasconde notifica Filament
 */
function hideFilamentNotification(notification) {
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 300);
}

/**
 * Inizializza chiusura notifiche Filament
 */
function initializeFilamentNotificationClose() {
    const closeButtons = document.querySelectorAll('[data-filament-notification-close]');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const notification = this.closest('.filament-notification');
            if (notification) {
                hideFilamentNotification(notification);
            }
        });
    });
}

/**
 * Utility: Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Utility: Throttle function
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ===========================================
// EXPORT PER UTILIZZO IN ALTRI MODULI
// ===========================================

window.FilamentSixteen = {
    showNotification: showFilamentNotification,
    hideNotification: hideFilamentNotification,
    openModal: openFilamentModal,
    closeModal: closeFilamentModal,
    showTooltip: showFilamentTooltip,
    hideTooltip: hideFilamentTooltip,
    validateForm: validateFilamentForm,
    showFieldError: showFilamentFieldError,
    clearFieldError: clearFilamentFieldError
};
