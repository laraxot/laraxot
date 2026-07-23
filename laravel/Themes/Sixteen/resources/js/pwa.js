/**
 * PWA (Progressive Web App) Manager
 * 
 * Gestisce la registrazione del service worker,
 * l'installazione dell'app e le notifiche push.
 */

class PWAManager {
    constructor() {
        this.serviceWorker = null;
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.isOnline = true;
        
        this.init();
    }
    
    /**
     * Inizializza il PWA Manager
     */
    init() {
        this.registerServiceWorker();
        this.setupInstallPrompt();
        this.setupOnlineStatus();
        this.setupUpdateAvailable();
        this.setupPushNotifications();
    }
    
    /**
     * Registra il Service Worker
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                
                this.serviceWorker = registration;
                console.log('[PWA] Service Worker registered successfully:', registration);
                
                // Gestisci aggiornamenti
                registration.addEventListener('updatefound', () => {
                    this.handleUpdateFound(registration);
                });
                
            } catch (error) {
                console.error('[PWA] Service Worker registration failed:', error);
            }
        } else {
            console.warn('[PWA] Service Worker not supported');
        }
    }
    
    /**
     * Configura il prompt di installazione
     */
    setupInstallPrompt() {
        // Intercetta l'evento beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[PWA] Install prompt triggered');
            e.preventDefault();
            this.deferredPrompt = e;
            
            // Mostra il pulsante di installazione
            this.showInstallButton();
        });
        
        // Gestisci l'evento appinstalled
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] App installed successfully');
            this.isInstalled = true;
            this.hideInstallButton();
            this.showInstallSuccessMessage();
        });
    }
    
    /**
     * Configura il monitoraggio dello stato online/offline
     */
    setupOnlineStatus() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.showOnlineMessage();
            this.syncOfflineData();
        });
        
        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showOfflineMessage();
        });
        
        // Verifica stato iniziale
        this.isOnline = navigator.onLine;
    }
    
    /**
     * Configura la gestione degli aggiornamenti
     */
    setupUpdateAvailable() {
        if (this.serviceWorker) {
            this.serviceWorker.addEventListener('controllerchange', () => {
                this.showUpdateAvailableMessage();
            });
        }
    }
    
    /**
     * Configura le notifiche push
     */
    async setupPushNotifications() {
        if ('Notification' in window && 'serviceWorker' in navigator) {
            // Richiedi permessi per le notifiche
            if (Notification.permission === 'default') {
                await this.requestNotificationPermission();
            }
        }
    }
    
    /**
     * Mostra il pulsante di installazione
     */
    showInstallButton() {
        const installButton = this.createInstallButton();
        document.body.appendChild(installButton);
        
        installButton.addEventListener('click', () => {
            this.installApp();
        });
    }
    
    /**
     * Crea il pulsante di installazione
     */
    createInstallButton() {
        const button = document.createElement('div');
        button.id = 'pwa-install-button';
        button.innerHTML = `
            <div class="pwa-install-banner">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <i class="fas fa-download me-2"></i>
                            <strong>Installa FixCity</strong>
                            <small class="d-block">Aggiungi alla schermata home per un accesso rapido</small>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm me-2" id="install-btn">
                                <i class="fas fa-plus me-1"></i>
                                Installa
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="dismiss-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Stili
        button.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 9999;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideUp 0.3s ease-out;
        `;
        
        // Aggiungi stili CSS
        if (!document.getElementById('pwa-styles')) {
            const style = document.createElement('style');
            style.id = 'pwa-styles';
            style.textContent = `
                @keyframes slideUp {
                    from { transform: translateY(100%); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
                .pwa-install-banner {
                    padding: 1rem;
                }
                @media (max-width: 768px) {
                    #pwa-install-button {
                        left: 10px;
                        right: 10px;
                        bottom: 10px;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        return button;
    }
    
    /**
     * Installa l'app
     */
    async installApp() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            
            console.log('[PWA] Install prompt outcome:', outcome);
            this.deferredPrompt = null;
            
            if (outcome === 'accepted') {
                this.hideInstallButton();
            }
        }
    }
    
    /**
     * Nasconde il pulsante di installazione
     */
    hideInstallButton() {
        const button = document.getElementById('pwa-install-button');
        if (button) {
            button.style.animation = 'slideDown 0.3s ease-in';
            setTimeout(() => button.remove(), 300);
        }
    }
    
    /**
     * Richiedi permessi per le notifiche
     */
    async requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();
            console.log('[PWA] Notification permission:', permission);
            return permission === 'granted';
        } catch (error) {
            console.error('[PWA] Error requesting notification permission:', error);
            return false;
        }
    }
    
    /**
     * Mostra messaggio di successo installazione
     */
    showInstallSuccessMessage() {
        this.showToast('App installata con successo!', 'success');
    }
    
    /**
     * Mostra messaggio di aggiornamento disponibile
     */
    showUpdateAvailableMessage() {
        const toast = this.createToast('Aggiornamento disponibile', 'info');
        toast.innerHTML += `
            <button class="btn btn-sm btn-outline-light ms-3" onclick="location.reload()">
                Aggiorna
            </button>
        `;
        document.body.appendChild(toast);
    }
    
    /**
     * Mostra messaggio online
     */
    showOnlineMessage() {
        this.showToast('Connessione ripristinata', 'success');
        this.syncOfflineData();
    }
    
    /**
     * Mostra messaggio offline
     */
    showOfflineMessage() {
        this.showToast('Modalità offline attiva', 'warning');
    }
    
    /**
     * Sincronizza dati offline
     */
    async syncOfflineData() {
        if (this.serviceWorker && this.isOnline) {
            try {
                await this.serviceWorker.sync.register('ticket-sync');
                console.log('[PWA] Offline data sync triggered');
            } catch (error) {
                console.error('[PWA] Sync registration failed:', error);
            }
        }
    }
    
    /**
     * Gestisci aggiornamento trovato
     */
    handleUpdateFound(registration) {
        const newWorker = registration.installing;
        if (newWorker) {
            newWorker.addEventListener('statechange', () => {
                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                    this.showUpdateAvailableMessage();
                }
            });
        }
    }
    
    /**
     * Crea un toast notification
     */
    createToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${this.getToastIcon(type)} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        
        return toast;
    }
    
    /**
     * Mostra un toast
     */
    showToast(message, type = 'info') {
        const toast = this.createToast(message, type);
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }
    
    /**
     * Ottieni icona per il toast
     */
    getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    
    /**
     * Verifica se l'app è installata
     */
    isAppInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches ||
               window.navigator.standalone === true ||
               document.referrer.includes('android-app://');
    }
    
    /**
     * Ottieni informazioni sull'app
     */
    getAppInfo() {
        return {
            isInstalled: this.isAppInstalled(),
            isOnline: this.isOnline,
            hasServiceWorker: !!this.serviceWorker,
            canInstall: !!this.deferredPrompt,
            notificationPermission: Notification.permission
        };
    }
}

// Inizializza PWA Manager quando il DOM è pronto
document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});

// Esporta per uso globale
window.PWAManager = PWAManager;









