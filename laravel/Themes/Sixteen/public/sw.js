/**
 * Service Worker per FixCity Platform
 * 
 * Implementa strategie di caching avanzate per migliorare
 * le performance e l'esperienza offline.
 */

const CACHE_NAME = 'fixcity-v1.0.0';
const STATIC_CACHE = 'fixcity-static-v1.0.0';
const DYNAMIC_CACHE = 'fixcity-dynamic-v1.0.0';
const API_CACHE = 'fixcity-api-v1.0.0';

// Risorse statiche da cachare immediatamente
const STATIC_ASSETS = [
  '/',
  '/manifest.json',
  '/css/app.css',
  '/js/app.js',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/offline.html'
];

// Strategie di caching
const CACHE_STRATEGIES = {
  // Cache First: per risorse statiche
  CACHE_FIRST: 'cache-first',
  // Network First: per API e contenuti dinamici
  NETWORK_FIRST: 'network-first',
  // Stale While Revalidate: per risorse che cambiano raramente
  STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
  // Network Only: per operazioni critiche
  NETWORK_ONLY: 'network-only'
};

// Installazione del Service Worker
self.addEventListener('install', (event) => {
  console.log('[SW] Installing Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Cache risorse statiche
      caches.open(STATIC_CACHE).then((cache) => {
        console.log('[SW] Caching static assets...');
        return cache.addAll(STATIC_ASSETS);
      }),
      // Skip waiting per attivazione immediata
      self.skipWaiting()
    ])
  );
});

// Attivazione del Service Worker
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Pulisci cache vecchie
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName !== STATIC_CACHE && 
                cacheName !== DYNAMIC_CACHE && 
                cacheName !== API_CACHE) {
              console.log('[SW] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      }),
      // Prendi controllo di tutti i client
      self.clients.claim()
    ])
  );
});

// Gestione delle richieste
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip richieste non HTTP/HTTPS
  if (!request.url.startsWith('http')) {
    return;
  }
  
  // Skip richieste di Chrome extension
  if (url.protocol === 'chrome-extension:') {
    return;
  }
  
  // Applica strategia di caching appropriata
  if (isStaticAsset(request)) {
    event.respondWith(handleCacheFirst(request));
  } else if (isApiRequest(request)) {
    event.respondWith(handleNetworkFirst(request));
  } else if (isPageRequest(request)) {
    event.respondWith(handleStaleWhileRevalidate(request));
  } else {
    event.respondWith(handleNetworkFirst(request));
  }
});

// Gestione notifiche push
self.addEventListener('push', (event) => {
  console.log('[SW] Push notification received');
  
  const options = {
    body: event.data ? event.data.text() : 'Nuova notifica da FixCity',
    icon: '/icons/icon-192x192.png',
    badge: '/icons/badge-72x72.png',
    vibrate: [200, 100, 200],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Visualizza',
        icon: '/icons/action-view.png'
      },
      {
        action: 'close',
        title: 'Chiudi',
        icon: '/icons/action-close.png'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('FixCity Platform', options)
  );
});

// Gestione click su notifiche
self.addEventListener('notificationclick', (event) => {
  console.log('[SW] Notification click received');
  
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/')
    );
  } else if (event.action === 'close') {
    // Chiudi notifica
    return;
  } else {
    // Click sulla notifica stessa
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

// Gestione sincronizzazione in background
self.addEventListener('sync', (event) => {
  console.log('[SW] Background sync:', event.tag);
  
  if (event.tag === 'ticket-sync') {
    event.waitUntil(syncTickets());
  }
});

// Helper functions
function isStaticAsset(request) {
  const url = new URL(request.url);
  return url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/);
}

function isApiRequest(request) {
  const url = new URL(request.url);
  return url.pathname.startsWith('/api/');
}

function isPageRequest(request) {
  return request.method === 'GET' && 
         request.headers.get('accept').includes('text/html');
}

// Strategia Cache First
async function handleCacheFirst(request) {
  try {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(STATIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.error('[SW] Cache First error:', error);
    return new Response('Risorsa non disponibile', { status: 503 });
  }
}

// Strategia Network First
async function handleNetworkFirst(request) {
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    console.log('[SW] Network failed, trying cache...');
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // Per le pagine, mostra offline page
    if (isPageRequest(request)) {
      return caches.match('/offline.html');
    }
    
    return new Response('Risorsa non disponibile offline', { status: 503 });
  }
}

// Strategia Stale While Revalidate
async function handleStaleWhileRevalidate(request) {
  const cache = await caches.open(DYNAMIC_CACHE);
  const cachedResponse = await cache.match(request);
  
  const fetchPromise = fetch(request).then((networkResponse) => {
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  });
  
  return cachedResponse || fetchPromise;
}

// Sincronizzazione ticket offline
async function syncTickets() {
  try {
    // Implementa logica di sincronizzazione
    console.log('[SW] Syncing offline tickets...');
    
    // Recupera ticket offline dal localStorage
    const offlineTickets = await getOfflineTickets();
    
    for (const ticket of offlineTickets) {
      try {
        await fetch('/api/tickets', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': await getCSRFToken()
          },
          body: JSON.stringify(ticket)
        });
        
        // Rimuovi ticket sincronizzato
        await removeOfflineTicket(ticket.id);
      } catch (error) {
        console.error('[SW] Failed to sync ticket:', error);
      }
    }
  } catch (error) {
    console.error('[SW] Sync error:', error);
  }
}

// Utility functions
async function getOfflineTickets() {
  // Implementa recupero da IndexedDB o localStorage
  return [];
}

async function removeOfflineTicket(ticketId) {
  // Implementa rimozione da IndexedDB o localStorage
}

async function getCSRFToken() {
  // Implementa recupero CSRF token
  return '';
}

// Gestione errori globali
self.addEventListener('error', (event) => {
  console.error('[SW] Global error:', event.error);
});

self.addEventListener('unhandledrejection', (event) => {
  console.error('[SW] Unhandled promise rejection:', event.reason);
});









