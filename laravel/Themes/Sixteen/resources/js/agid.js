/**
 * AGID JavaScript Framework - Tema Sixteen
 * Accessibility and User Experience enhancements for AGID compliance
 */

// AGID Accessibility Manager
class AGIDAccessibility {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupFocusManagement();
        this.setupKeyboardNavigation();
        this.setupFormEnhancements();
        this.setupSkipLinks();
        this.setupAriaLiveRegions();
    }
    
    // Enhanced Focus Management
    setupFocusManagement() {
        // Focus first input on page load
        document.addEventListener('DOMContentLoaded', () => {
            const firstInput = document.querySelector('input[type="email"], input[type="text"], input[type="password"]');
            if (firstInput && !firstInput.value && !document.activeElement || document.activeElement === document.body) {
                setTimeout(() => firstInput.focus(), 100);
            }
        });
        
        // Focus trap for modals
        this.setupModalFocusTrap();
        
        // Focus visible polyfill
        this.setupFocusVisible();
    }
    
    setupModalFocusTrap() {
        const modals = document.querySelectorAll('[role="dialog"]');
        
        modals.forEach(modal => {
            const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
            const focusableContent = modal.querySelectorAll(focusableElements);
            const firstFocusableElement = focusableContent[0];
            const lastFocusableElement = focusableContent[focusableContent.length - 1];
            
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstFocusableElement) {
                            lastFocusableElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastFocusableElement) {
                            firstFocusableElement.focus();
                            e.preventDefault();
                        }
                    }
                }
                
                if (e.key === 'Escape') {
                    this.closeModal(modal);
                }
            });
        });
    }
    
    setupFocusVisible() {
        // Add focus-visible polyfill behavior
        let hadKeyboardEvent = true;
        
        const keyboardThrottledEventListener = (e) => {
            if (e.metaKey || e.altKey || e.ctrlKey) return;
            hadKeyboardEvent = true;
        };
        
        const pointerEventListener = () => {
            hadKeyboardEvent = false;
        };
        
        document.addEventListener('keydown', keyboardThrottledEventListener, true);
        document.addEventListener('mousedown', pointerEventListener, true);
        document.addEventListener('pointerdown', pointerEventListener, true);
        document.addEventListener('touchstart', pointerEventListener, true);
        
        document.addEventListener('focus', (e) => {
            if (hadKeyboardEvent || e.target.matches(':focus-visible')) {
                e.target.classList.add('focus-visible');
            }
        }, true);
        
        document.addEventListener('blur', (e) => {
            e.target.classList.remove('focus-visible');
        }, true);
    }
    
    // Keyboard Navigation Enhancement
    setupKeyboardNavigation() {
        // Arrow key navigation for menus
        const menus = document.querySelectorAll('[role="menu"]');
        
        menus.forEach(menu => {
            const menuItems = menu.querySelectorAll('[role="menuitem"]');
            let currentIndex = 0;
            
            menu.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentIndex = (currentIndex + 1) % menuItems.length;
                        menuItems[currentIndex].focus();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        currentIndex = (currentIndex - 1 + menuItems.length) % menuItems.length;
                        menuItems[currentIndex].focus();
                        break;
                    case 'Home':
                        e.preventDefault();
                        currentIndex = 0;
                        menuItems[currentIndex].focus();
                        break;
                    case 'End':
                        e.preventDefault();
                        currentIndex = menuItems.length - 1;
                        menuItems[currentIndex].focus();
                        break;
                }
            });
        });
        
        // Enter key for buttons and links
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.target.matches('a, button')) {
                e.target.click();
            }
        });
    }
    
    // Form Enhancements
    setupFormEnhancements() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Real-time validation
            this.setupFormValidation(form);
            
            // Submit button state management
            this.setupSubmitButtonState(form);
            
            // Required field indicators
            this.setupRequiredFieldIndicators(form);
        });
    }
    
    setupFormValidation(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                if (input.classList.contains('error')) {
                    this.validateField(input);
                }
            });
        });
    }
    
    validateField(field) {
        const isValid = field.checkValidity();
        const errorElement = field.parentNode.querySelector('.agid-form-error');
        
        if (isValid) {
            field.classList.remove('error');
            field.setAttribute('aria-invalid', 'false');
            if (errorElement) {
                errorElement.remove();
            }
        } else {
            field.classList.add('error');
            field.setAttribute('aria-invalid', 'true');
            
            if (!errorElement) {
                const error = document.createElement('div');
                error.className = 'agid-form-error';
                error.textContent = field.validationMessage;
                error.id = `${field.id}-error`;
                field.setAttribute('aria-describedby', error.id);
                field.parentNode.appendChild(error);
            }
        }
    }
    
    setupSubmitButtonState(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton) {
            form.addEventListener('submit', () => {
                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');
                submitButton.classList.add('agid-loading');
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.setAttribute('aria-busy', 'false');
                    submitButton.classList.remove('agid-loading');
                }, 5000);
            });
        }
    }
    
    setupRequiredFieldIndicators(form) {
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            const label = form.querySelector(`label[for="${field.id}"]`);
            if (label && !label.querySelector('.required-indicator')) {
                const indicator = document.createElement('span');
                indicator.className = 'required-indicator';
                indicator.textContent = ' *';
                indicator.setAttribute('aria-label', 'campo obbligatorio');
                indicator.style.color = '#D73527';
                label.appendChild(indicator);
            }
        });
    }
    
    // Skip Links Enhancement
    setupSkipLinks() {
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
    
    // ARIA Live Regions
    setupAriaLiveRegions() {
        // Create live region for dynamic announcements
        if (!document.getElementById('agid-live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'agid-live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }
    }
    
    // Public Methods
    announce(message, priority = 'polite') {
        const liveRegion = document.getElementById('agid-live-region');
        if (liveRegion) {
            liveRegion.setAttribute('aria-live', priority);
            liveRegion.textContent = message;
            
            // Clear after announcement
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }
    
    closeModal(modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        
        // Return focus to trigger element
        const trigger = document.querySelector(`[aria-controls="${modal.id}"]`);
        if (trigger) {
            trigger.focus();
        }
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `agid-alert agid-alert-${type}`;
        notification.setAttribute('role', 'alert');
        notification.textContent = message;
        
        // Insert at top of main content
        const main = document.getElementById('main-content');
        if (main) {
            main.insertBefore(notification, main.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }
}

// AGID Performance Monitor
class AGIDPerformance {
    constructor() {
        this.init();
    }
    
    init() {
        this.monitorPageLoad();
        this.monitorInteractions();
    }
    
    monitorPageLoad() {
        window.addEventListener('load', () => {
            // Monitor Core Web Vitals
            this.measureCLS();
            this.measureFID();
            this.measureLCP();
        });
    }
    
    measureCLS() {
        let cls = 0;
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (!entry.hadRecentInput) {
                    cls += entry.value;
                }
            }
        }).observe({ type: 'layout-shift', buffered: true });
    }
    
    measureFID() {
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                const fid = entry.processingStart - entry.startTime;
                // Log or send to analytics
            }
        }).observe({ type: 'first-input', buffered: true });
    }
    
    measureLCP() {
        new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            const lastEntry = entries[entries.length - 1];
            const lcp = lastEntry.startTime;
            // Log or send to analytics
        }).observe({ type: 'largest-contentful-paint', buffered: true });
    }
    
    monitorInteractions() {
        // Monitor form interactions
        document.addEventListener('submit', (e) => {
            const formId = e.target.id || 'unknown-form';
            // Log form submission
        });
        
        // Monitor navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('a[href]')) {
                const href = e.target.getAttribute('href');
                // Log navigation
            }
        });
    }
}

// Initialize AGID Framework
document.addEventListener('DOMContentLoaded', () => {
    window.AGID = {
        accessibility: new AGIDAccessibility(),
        performance: new AGIDPerformance()
    };
    
    // Global helper functions
    window.agidAnnounce = (message, priority) => {
        window.AGID.accessibility.announce(message, priority);
    };
    
    window.agidNotify = (message, type) => {
        window.AGID.accessibility.showNotification(message, type);
    };
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AGIDAccessibility, AGIDPerformance };
}
