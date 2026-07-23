# 10 - App Mobile PWA

## Panoramica
Progressive Web App (PWA) completa per dipendenti con funzionalità offline, notifiche push, geolocalizzazione per timbrature e interfaccia nativa-like per tutte le funzioni principali del sistema.

## Obiettivi
- Fornire esperienza mobile nativa con tecnologie web
- Supportare funzionalità offline per timbrature e consultazioni
- Implementare notifiche push per comunicazioni urgenti
- Integrare geolocalizzazione per controllo presenze
- Sincronizzazione automatica quando online
- Interfaccia ottimizzata per dispositivi mobili

## Funzionalità da Implementare

### 1. Configurazione PWA Base

#### 1.1 Setup PWA Infrastructure
**Obiettivo**: Configurare l'infrastruttura PWA con Service Worker e Manifest

**Implementazione Step-by-Step**:

1. **Creare Web App Manifest**
```json
// public/manifest.json
{
  "name": "Employee Portal",
  "short_name": "EmpPortal",
  "description": "Sistema gestione dipendenti aziendale",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#3B82F6",
  "orientation": "portrait",
  "icons": [
    {
      "src": "/images/icons/icon-72x72.png",
      "sizes": "72x72",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-96x96.png",
      "sizes": "96x96",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-128x128.png",
      "sizes": "128x128",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-144x144.png",
      "sizes": "144x144",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-152x152.png",
      "sizes": "152x152",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-192x192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-384x384.png",
      "sizes": "384x384",
      "type": "image/png"
    },
    {
      "src": "/images/icons/icon-512x512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "shortcuts": [
    {
      "name": "Timbra",
      "short_name": "Timbra",
      "description": "Registra entrata/uscita",
      "url": "/mobile/attendance",
      "icons": [{ "src": "/images/icons/clock-icon.png", "sizes": "96x96" }]
    },
    {
      "name": "Ferie",
      "short_name": "Ferie",
      "description": "Richiedi ferie",
      "url": "/mobile/leave-request",
      "icons": [{ "src": "/images/icons/calendar-icon.png", "sizes": "96x96" }]
    }
  ]
}
```

2. **Creare Service Worker**
```javascript
// public/sw.js
const CACHE_NAME = 'employee-portal-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/mobile/attendance',
  '/mobile/dashboard',
  '/mobile/leave-request',
  '/images/icons/icon-192x192.png'
];

// Install event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Fetch event
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Return cached version or fetch from network
        return response || fetch(event.request);
      }
    )
  );
});

// Background sync for offline actions
self.addEventListener('sync', event => {
  if (event.tag === 'attendance-sync') {
    event.waitUntil(syncAttendance());
  }
  if (event.tag === 'leave-request-sync') {
    event.waitUntil(syncLeaveRequests());
  }
});

// Push notifications
self.addEventListener('push', event => {
  const options = {
    body: event.data.text(),
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/badge-72x72.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Visualizza',
        icon: '/images/icons/checkmark.png'
      },
      {
        action: 'close',
        title: 'Chiudi',
        icon: '/images/icons/xmark.png'
      }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('Employee Portal', options)
  );
});

async function syncAttendance() {
  const pendingAttendances = await getStoredAttendances();
  
  for (const attendance of pendingAttendances) {
    try {
      await fetch('/api/mobile/attendance', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${attendance.token}`
        },
        body: JSON.stringify(attendance.data)
      });
      
      // Remove from local storage after successful sync
      await removeStoredAttendance(attendance.id);
    } catch (error) {
      console.error('Sync failed:', error);
    }
  }
}
```

3. **Creare Controller API Mobile**
```php
// app/Http/Controllers/Api/MobileController.php
class MobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function dashboard(Request $request)
    {
        $employee = $request->user()->employee;
        
        return response()->json([
            'employee' => $employee->load(['department', 'role']),
            'today_attendance' => $this->getTodayAttendance($employee),
            'pending_approvals' => $this->getPendingApprovals($employee),
            'recent_communications' => $this->getRecentCommunications($employee),
            'leave_balance' => $this->getLeaveBalance($employee)
        ]);
    }
    
    public function recordAttendance(Request $request)
    {
        $request->validate([
            'type' => 'required|in:check_in,check_out',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $employee = $request->user()->employee;
        
        // Verifica geolocalizzazione se richiesta
        if ($request->has(['latitude', 'longitude'])) {
            $isValidLocation = $this->validateLocation(
                $request->latitude,
                $request->longitude,
                $employee
            );
            
            if (!$isValidLocation) {
                return response()->json([
                    'error' => 'Posizione non valida per la timbratura'
                ], 422);
            }
        }
        
        $attendance = app(AttendanceService::class)->recordAttendance(
            $employee,
            $request->type,
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'notes' => $request->notes,
                'device_type' => 'mobile'
            ]
        );
        
        return response()->json([
            'success' => true,
            'attendance' => $attendance,
            'message' => $request->type === 'check_in' ? 'Entrata registrata' : 'Uscita registrata'
        ]);
    }
    
    private function validateLocation(float $lat, float $lng, Employee $employee): bool
    {
        $allowedLocations = $employee->department->allowed_locations ?? [];
        
        foreach ($allowedLocations as $location) {
            $distance = $this->calculateDistance(
                $lat, $lng,
                $location['latitude'], $location['longitude']
            );
            
            if ($distance <= $location['radius']) {
                return true;
            }
        }
        
        return false;
    }
}
```

### 2. Interfaccia Mobile Ottimizzata

#### 2.1 Dashboard Mobile
**Obiettivo**: Creare dashboard mobile-first con accesso rapido alle funzioni principali

**Implementazione**:

1. **Creare Layout Mobile**
```blade
{{-- resources/views/mobile/layout.blade.php --}}
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3B82F6">
    <title>Employee Portal</title>
    
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    
    @vite(['resources/css/mobile.css', 'resources/js/mobile.js'])
</head>
<body class="bg-gray-50">
    <div id="app">
        {{-- Header --}}
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="flex items-center justify-between p-4">
                <h1 class="text-lg font-semibold">{{ $title ?? 'Employee Portal' }}</h1>
                <div class="flex items-center space-x-2">
                    <button id="sync-btn" class="p-2 rounded-full hover:bg-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <div class="w-2 h-2 rounded-full" id="connection-status"></div>
                </div>
            </div>
        </header>
        
        {{-- Main Content --}}
        <main class="pb-20">
            @yield('content')
        </main>
        
        {{-- Bottom Navigation --}}
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
            <div class="flex justify-around py-2">
                <a href="/mobile/dashboard" class="flex flex-col items-center p-2 {{ request()->is('mobile/dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Dashboard</span>
                </a>
                
                <a href="/mobile/attendance" class="flex flex-col items-center p-2 {{ request()->is('mobile/attendance') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Timbra</span>
                </a>
                
                <a href="/mobile/leave" class="flex flex-col items-center p-2 {{ request()->is('mobile/leave') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Ferie</span>
                </a>
                
                <a href="/mobile/profile" class="flex flex-col items-center p-2 {{ request()->is('mobile/profile') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs mt-1">Profilo</span>
                </a>
            </div>
        </nav>
    </div>
    
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => console.log('SW registered'))
                .catch(error => console.log('SW registration failed'));
        }
        
        // Update connection status
        function updateConnectionStatus() {
            const status = document.getElementById('connection-status');
            if (navigator.onLine) {
                status.className = 'w-2 h-2 rounded-full bg-green-500';
            } else {
                status.className = 'w-2 h-2 rounded-full bg-red-500';
            }
        }
        
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        updateConnectionStatus();
    </script>
</body>
</html>
```

2. **Creare Dashboard Mobile**
```blade
{{-- resources/views/mobile/dashboard.blade.php --}}
@extends('mobile.layout', ['title' => 'Dashboard'])

@section('content')
<div class="p-4 space-y-4">
    {{-- Timbratura Rapida --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Timbratura</h2>
        <div class="grid grid-cols-2 gap-4">
            <button id="check-in-btn" class="bg-green-500 text-white py-3 px-4 rounded-lg font-medium">
                Entrata
            </button>
            <button id="check-out-btn" class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium">
                Uscita
            </button>
        </div>
        <div id="attendance-status" class="mt-4 text-sm text-gray-600"></div>
    </div>
    
    {{-- Stato Oggi --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Oggi</h2>
        <div class="grid grid-cols-2 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold text-blue-600" id="hours-worked">0:00</div>
                <div class="text-sm text-gray-600">Ore Lavorate</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-green-600" id="break-time">0:30</div>
                <div class="text-sm text-gray-600">Pausa</div>
            </div>
        </div>
    </div>
    
    {{-- Azioni Rapide --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Azioni Rapide</h2>
        <div class="grid grid-cols-2 gap-4">
            <a href="/mobile/leave-request" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg">
                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium">Richiedi Ferie</span>
            </a>
            
            <a href="/mobile/expenses" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg">
                <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                <span class="text-sm font-medium">Note Spese</span>
            </a>
        </div>
    </div>
    
    {{-- Comunicazioni Recenti --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Comunicazioni</h2>
        <div id="recent-communications" class="space-y-3">
            <!-- Popolato via JavaScript -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    
    document.getElementById('check-in-btn').addEventListener('click', () => recordAttendance('check_in'));
    document.getElementById('check-out-btn').addEventListener('click', () => recordAttendance('check_out'));
});

async function recordAttendance(type) {
    try {
        const position = await getCurrentPosition();
        
        const response = await fetch('/api/mobile/attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getAuthToken()}`
            },
            body: JSON.stringify({
                type: type,
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            updateAttendanceStatus();
        } else {
            showNotification(result.error, 'error');
        }
    } catch (error) {
        // Store offline for sync later
        storeOfflineAttendance(type);
        showNotification('Timbratura salvata offline', 'info');
    }
}

function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        });
    });
}
</script>
@endsection
```

### 3. Funzionalità Offline

#### 3.1 Sincronizzazione Dati
**Obiettivo**: Permettere uso offline con sincronizzazione automatica

**Implementazione**:

1. **Creare Service OfflineService**
```javascript
// resources/js/mobile/offline-service.js
class OfflineService {
    constructor() {
        this.dbName = 'EmployeePortalDB';
        this.version = 1;
        this.db = null;
        this.init();
    }
    
    async init() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.version);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                resolve(this.db);
            };
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Store per timbrature offline
                if (!db.objectStoreNames.contains('attendances')) {
                    const attendanceStore = db.createObjectStore('attendances', { keyPath: 'id', autoIncrement: true });
                    attendanceStore.createIndex('timestamp', 'timestamp', { unique: false });
                }
                
                // Store per richieste ferie offline
                if (!db.objectStoreNames.contains('leaveRequests')) {
                    const leaveStore = db.createObjectStore('leaveRequests', { keyPath: 'id', autoIncrement: true });
                    leaveStore.createIndex('timestamp', 'timestamp', { unique: false });
                }
                
                // Cache per dati consultabili offline
                if (!db.objectStoreNames.contains('cache')) {
                    db.createObjectStore('cache', { keyPath: 'key' });
                }
            };
        });
    }
    
    async storeAttendance(attendanceData) {
        const transaction = this.db.transaction(['attendances'], 'readwrite');
        const store = transaction.objectStore('attendances');
        
        const data = {
            ...attendanceData,
            timestamp: Date.now(),
            synced: false
        };
        
        return store.add(data);
    }
    
    async getPendingAttendances() {
        const transaction = this.db.transaction(['attendances'], 'readonly');
        const store = transaction.objectStore('attendances');
        const request = store.getAll();
        
        return new Promise((resolve, reject) => {
            request.onsuccess = () => {
                const attendances = request.result.filter(a => !a.synced);
                resolve(attendances);
            };
            request.onerror = () => reject(request.error);
        });
    }
    
    async markAttendanceSynced(id) {
        const transaction = this.db.transaction(['attendances'], 'readwrite');
        const store = transaction.objectStore('attendances');
        
        const getRequest = store.get(id);
        getRequest.onsuccess = () => {
            const data = getRequest.result;
            data.synced = true;
            store.put(data);
        };
    }
    
    async cacheData(key, data) {
        const transaction = this.db.transaction(['cache'], 'readwrite');
        const store = transaction.objectStore('cache');
        
        return store.put({
            key: key,
            data: data,
            timestamp: Date.now()
        });
    }
    
    async getCachedData(key) {
        const transaction = this.db.transaction(['cache'], 'readonly');
        const store = transaction.objectStore('cache');
        const request = store.get(key);
        
        return new Promise((resolve, reject) => {
            request.onsuccess = () => {
                const result = request.result;
                if (result && this.isCacheValid(result.timestamp)) {
                    resolve(result.data);
                } else {
                    resolve(null);
                }
            };
            request.onerror = () => reject(request.error);
        });
    }
    
    isCacheValid(timestamp) {
        const maxAge = 24 * 60 * 60 * 1000; // 24 ore
        return (Date.now() - timestamp) < maxAge;
    }
}

const offlineService = new OfflineService();
```

### 4. Notifiche Push

#### 4.1 Sistema Push Notifications
**Obiettivo**: Inviare notifiche push per comunicazioni importanti

**Implementazione**:

1. **Configurare Push Notifications**
```php
// app/Services/PushNotificationService.php
class PushNotificationService
{
    public function sendToEmployee(Employee $employee, string $title, string $body, array $data = []): void
    {
        $subscriptions = $employee->pushSubscriptions;
        
        foreach ($subscriptions as $subscription) {
            $this->sendNotification($subscription, $title, $body, $data);
        }
    }
    
    private function sendNotification(PushSubscription $subscription, string $title, string $body, array $data): void
    {
        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'icon' => '/images/icons/icon-192x192.png',
            'badge' => '/images/icons/badge-72x72.png',
            'data' => $data,
            'actions' => [
                ['action' => 'view', 'title' => 'Visualizza'],
                ['action' => 'dismiss', 'title' => 'Ignora']
            ]
        ]);
        
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('app.url'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key')
            ]
        ]);
        
        $webPush->sendOneNotification(
            Subscription::create([
                'endpoint' => $subscription->endpoint,
                'publicKey' => $subscription->p256dh_key,
                'authToken' => $subscription->auth_token
            ]),
            $payload
        );
    }
}
```

## Checklist Implementazione

### Phase 1: PWA Infrastructure
- [ ] Configurare Web App Manifest
- [ ] Implementare Service Worker
- [ ] Setup cache strategies
- [ ] Configurare offline support

### Phase 2: Mobile UI
- [ ] Creare layout mobile responsive
- [ ] Implementare bottom navigation
- [ ] Dashboard mobile ottimizzata
- [ ] Form mobile-friendly

### Phase 3: Offline Functionality
- [ ] Implementare IndexedDB storage
- [ ] Sistema sincronizzazione automatica
- [ ] Cache intelligente dati
- [ ] Gestione conflitti sync

### Phase 4: Push Notifications
- [ ] Configurare VAPID keys
- [ ] Implementare subscription management
- [ ] Sistema invio notifiche
- [ ] Gestione azioni notifiche

### Phase 5: Advanced Features
- [ ] Geolocalizzazione timbrature
- [ ] Biometric authentication
- [ ] Background sync
- [ ] Performance optimization

## Note Tecniche

### Performance
- Lazy loading componenti
- Image optimization
- Code splitting
- Service Worker caching

### Sicurezza
- HTTPS obbligatorio per PWA
- Token authentication
- Secure storage dati sensibili
- Validation geolocalizzazione

### UX/UI
- Touch-friendly interface
- Gesture support
- Loading states
- Offline indicators

Questa PWA fornirà un'esperienza mobile completa e nativa per tutti i dipendenti.
