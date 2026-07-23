<!DOCTYPE html>
<html lang="it" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#0066cc">
    <meta name="description" content="FixCity Platform - Connessione offline">
    
    <title>Offline - FixCity Platform</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="{{ asset('js/app.js') }}" as="script">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- PWA Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="FixCity">
</head>
<body>
    {{-- Wrapper con ID per styling specifico pagina offline --}}
    <div id="offline-wrapper" class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-12 col-md-8 col-lg-6 mx-auto text-center">
                <!-- Offline Icon -->
                <div class="offline-icon mb-4">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#dc3545" stroke-width="2"/>
                        <path d="M8 12l2 2 4-4" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 2a10 10 0 0 0-10 10" stroke="#6c757d" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                
                <!-- Offline Message -->
                <h1 class="h2 mb-3 text-primary">Connessione Offline</h1>
                <p class="lead mb-4">
                    Sembra che tu non abbia una connessione internet attiva. 
                    Non preoccuparti, puoi comunque utilizzare alcune funzionalità di FixCity.
                </p>
                
                <!-- Available Features -->
                <div class="offline-features mb-4">
                    <h3 class="h5 mb-3">Funzionalità disponibili offline:</h3>
                    <div class="row">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-plus-circle text-success mb-2" style="font-size: 2rem;"></i>
                                <p class="small mb-0">Crea Ticket</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-list text-info mb-2" style="font-size: 2rem;"></i>
                                <p class="small mb-0">Visualizza Ticket</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-edit text-warning mb-2" style="font-size: 2rem;"></i>
                                <p class="small mb-0">Modifica Ticket</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-sync text-primary mb-2" style="font-size: 2rem;"></i>
                                <p class="small mb-0">Sincronizza</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="offline-actions">
                    <button type="button" class="btn btn-primary btn-lg me-3" onclick="retryConnection()">
                        <i class="fas fa-wifi me-2"></i>
                        Riprova Connessione
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-lg" onclick="goToApp()">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continua Offline
                    </button>
                </div>
                
                <!-- Connection Status -->
                <div class="connection-status mt-4">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="connection-status">Verificando connessione...</span>
                    </div>
                </div>
                
                <!-- Sync Status -->
                <div class="sync-status mt-3" id="sync-status" style="display: none;">
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-sync-alt fa-spin me-2"></i>
                        Sincronizzazione in corso...
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Connection retry logic
        function retryConnection() {
            const statusEl = document.getElementById('connection-status');
            statusEl.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verificando connessione...';
            
            // Test connection
            fetch('/', { method: 'HEAD', cache: 'no-cache' })
                .then(() => {
                    statusEl.innerHTML = '<i class="fas fa-check-circle me-2"></i>Connessione ripristinata!';
                    statusEl.parentElement.className = 'alert alert-success';
                    
                    // Redirect to app after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                })
                .catch(() => {
                    statusEl.innerHTML = '<i class="fas fa-times-circle me-2"></i>Connessione non disponibile';
                    statusEl.parentElement.className = 'alert alert-danger';
                });
        }
        
        // Go to app offline
        function goToApp() {
            // Check if we have cached data
            if ('serviceWorker' in navigator && 'caches' in window) {
                caches.open('fixcity-static-v1.0.0').then(cache => {
                    cache.match('/').then(response => {
                        if (response) {
                            window.location.href = '/';
                        } else {
                            showOfflineMessage();
                        }
                    });
                });
            } else {
                showOfflineMessage();
            }
        }
        
        // Show offline message
        function showOfflineMessage() {
            alert('Nessun dato disponibile offline. Riprova quando avrai una connessione.');
        }
        
        // Connection status monitoring
        function updateConnectionStatus() {
            const statusEl = document.getElementById('connection-status');
            
            if (navigator.onLine) {
                statusEl.innerHTML = '<i class="fas fa-wifi me-2"></i>Connesso';
                statusEl.parentElement.className = 'alert alert-success';
                
                // Show sync status
                const syncStatus = document.getElementById('sync-status');
                syncStatus.style.display = 'block';
                
                // Trigger sync
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then(registration => {
                        registration.sync.register('ticket-sync');
                    });
                }
            } else {
                statusEl.innerHTML = '<i class="fas fa-wifi-slash me-2"></i>Disconnesso';
                statusEl.parentElement.className = 'alert alert-danger';
            }
        }
        
        // Event listeners
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        
        // Initial status check
        updateConnectionStatus();
        
        // Periodic connection check
        setInterval(() => {
            if (navigator.onLine) {
                fetch('/', { method: 'HEAD', cache: 'no-cache' })
                    .then(() => updateConnectionStatus())
                    .catch(() => {
                        // Connection test failed
                    });
            }
        }, 5000);
    </script>
    
    <style>
        #offline-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Titillium Web', sans-serif;
        }
        
        .offline-icon svg {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .feature-item {
            padding: 1rem;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.8);
            transition: transform 0.2s;
        }
        
        .feature-item:hover {
            transform: translateY(-2px);
        }
        
        .offline-actions .btn {
            min-width: 200px;
        }
        
        .connection-status .alert {
            border: none;
            border-radius: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .offline-actions .btn {
                min-width: auto;
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .offline-actions .btn:last-child {
                margin-bottom: 0;
            }
        }
    </style>
</body>
</html>
