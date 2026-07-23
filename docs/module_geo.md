# Modulo Geo

## Panoramica
Il modulo Geo fornisce funzionalità per la gestione di dati geografici, inclusi:
- Calcolo distanze tra punti
- Geocodifica inversa (coordinate → indirizzo)
- Calcolo elevazione
- Clustering di posizioni
- Integrazione con vari servizi di mappe (Google Maps, Bing Maps, Mapbox)

## Struttura
```
Modules/Geo/
├── Actions/
│   ├── Bing/                 # Azioni per Bing Maps
│   ├── GoogleMaps/          # Azioni per Google Maps
│   ├── Mapbox/              # Azioni per Mapbox
│   ├── Elevation/           # Azioni per elevazione
│   └── ...                  # Altre azioni
├── Datas/                   # Data Transfer Objects
│   ├── GoogleMaps/         # DTO per Google Maps
│   └── ...                 # Altri DTO
├── Exceptions/             # Eccezioni personalizzate
├── Services/              # Servizi di supporto
│   ├── BaseGeoService.php # Classe base per servizi
│   └── ...               # Servizi specifici
└── config/               # Configurazioni
    └── config.php       # Configurazione principale
```

## Servizi

### BaseGeoService
Classe base astratta che fornisce funzionalità comuni per tutti i servizi geografici:
- Gestione configurazione API key
- Rate limiting delle richieste
- Cache dei risultati
- Retry automatico in caso di errori
- Timeout configurabile

### GoogleMapsService
Implementazione specifica per Google Maps che estende BaseGeoService:
- Geocodifica inversa
- Calcolo matrice distanze
- Calcolo elevazione
- Gestione errori specifica

## Configurazione
```php
// config/geo.php
return [
    'api_keys' => [
        'google_maps' => env('GOOGLE_MAPS_API_KEY'),
        'bing_maps' => env('BING_MAPS_API_KEY'),
        'mapbox' => env('MAPBOX_API_KEY'),
    ],
    'rate_limits' => [
        'google_maps' => [
            'requests_per_second' => 50,
            'burst' => 100,
        ],
        // ... configurazioni per altri servizi
    ],
    'cache' => [
        'enabled' => true,
        'ttl' => 86400, // 24 ore
        'prefix' => 'geo_',
    ],
    'http_client' => [
        'timeout' => 5.0,
        'retry' => [
            'times' => 3,
            'sleep' => 100,
            'when' => [
                'ConnectionException',
                'RequestException',
            ],
        ],
    ],
];
```

## Data Transfer Objects
### LocationData
- Gestisce coordinate geografiche (latitudine/longitudine)
- Supporta nome e indirizzo opzionali
- Implementa validazione coordinate
- Estende `Spatie\LaravelData\Data`

### AddressData
- Gestisce dati completi degli indirizzi
- Supporta formattazione indirizzo
- Include dettagli come via, città, CAP, ecc.
- Estende `Spatie\LaravelData\Data`

### MapboxMapData e BingMapData
- Gestiscono dati grezzi dai rispettivi servizi
- Implementano conversione array standardizzata
- Estendono `Spatie\LaravelData\Data`

## Azioni Principali

### CalculateDistanceAction
- Calcola distanza tra due punti
- Utilizza Google Maps Distance Matrix
- Supporta formattazione distanze
- Implementa validazione coordinate

### GetElevationAction
- Recupera elevazione per coordinate
- Utilizza Google Maps Elevation API
- Supporta formattazione elevazione
- Implementa validazione coordinate

### ClusterLocationsAction
- Raggruppa posizioni vicine
- Calcola centri dei cluster
- Supporta distanza massima configurabile

## Gestione Errori
Il modulo implementa diverse eccezioni personalizzate:
- `InvalidLocationException`
- `DistanceCalculationException`
- `GoogleMapsApiException`

## Best Practices
1. Validare sempre le coordinate prima dell'uso
2. Gestire gli errori delle API esterne
3. Utilizzare i DTO per la standardizzazione dei dati
4. Implementare retry per le chiamate API
5. Cachare i risultati quando possibile
6. Configurare correttamente i rate limit
7. Monitorare l'utilizzo delle API

## Troubleshooting
### Problemi Comuni
1. API key non configurate o non valide
2. Limiti di quota delle API superati
3. Coordinate non valide
4. Timeout nelle chiamate API
5. Cache piena o non funzionante

### Soluzioni
1. Verificare la configurazione delle API key
2. Implementare rate limiting
3. Validare input prima delle chiamate
4. Implementare timeout e retry
5. Monitorare e pulire la cache regolarmente

## Monitoraggio
- Log delle chiamate API
- Metriche di cache hit/miss
- Monitoraggio rate limit
- Tracciamento errori
- Performance delle chiamate 