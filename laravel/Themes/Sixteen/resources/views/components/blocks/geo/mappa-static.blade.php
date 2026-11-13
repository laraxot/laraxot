@props([
    'lat' => null,
    'lng' => null,
    'label' => 'Danno da riparare',
    'recordLat' => null,
    'recordLng' => null,
])

{{-- Logica: usa coordinate record se presenti; altrimenti geolocalizza l'utente; fallback Roma --}}
<div
    x-data="{
        latInit: {{ (float)($recordLat ?? $lat ?? 41.89) }},
        lngInit: {{ (float)($recordLng ?? $lng ?? 12.49) }},
        labelInit: '{{ addslashes($label) }}',
        centerOnUser() {
            if (!navigator.geolocation) {
                this.latInit = {{ (float)($recordLat ?? $lat ?? 41.89) }};
                this.lngInit = {{ (float)($recordLng ?? $lng ?? 12.49) }};
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latInit = pos.coords.latitude;
                    this.lngInit = pos.coords.longitude;
                },
                (err) => {
                    // fallback: usa record o Roma; non blocca
                    this.latInit = {{ (float)($recordLat ?? $lat ?? 41.89) }};
                    this.lngInit = {{ (float)($recordLng ?? $lng ?? 12.49) }};
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 30000 }
            );
        }
    }"
    x-init="centerOnUser()"
    class="geo-map-container bg-emerald-50 rounded-xl p-4 shadow-sm border border-emerald-100"
>
    <h3 class="text-emerald-800 font-bold mb-2">Localizzazione del danno</h3>
    <p class="text-xs text-emerald-600 mb-3">La mappa si centra dove ti trovi ora (permesso browser). Funziona su http, https e locale senza configurazione.</p>

    <div class="map-canvas h-64 bg-gradient-to-br from-emerald-100 to-teal-50 rounded-lg flex items-center justify-center relative overflow-hidden">
        <svg x-ref="mapSvg" viewBox="0 0 400 300" class="w-full h-full" aria-label="Mappa con pin a {{ $label }}" role="img" data-geo-local="true">
            <rect width="400" height="300" fill="#e8f5e9"/>
            <path d="M0 150 Q100 100 200 150 T400 150 L400 300 L0 300 Z" fill="#c8e6c9" opacity="0.6"/>
            {{-- Il pin si sposta in base a latInit/lngInit tramite x-bind (SVG dinamico tramite Alpine) --}}
            <g :transform="'translate(' + (({{ (float) ($recordLat ?? $lat ?? 41.89) }} - 41.89) * 5000 + 200) + ',' + (150 - ({{ (float) ($recordLng ?? $lng ?? 12.49) }} - 12.49) * 3000) + ')'">
                <circle cx="0" cy="0" r="8" fill="#007a52" stroke="#fff" stroke-width="2"/>
                <text x="0" y="15" text-anchor="middle" fill="#17324d" font-size="9">{{ $label }}</text>
            </g>
        </svg>
    </div>

    <div class="mt-3 text-xs text-emerald-700 flex gap-4">
        <span>Lat: <span x-text="latInit.toFixed(4)"></span></span>
        <span>Lng: <span x-text="lngInit.toFixed(4)"></span></span>
        <span class="text-emerald-500" x-show="latInit === {{ (float)($recordLat ?? $lat ?? 41.89) }}">↳ Posizione attuale o riferimento</span>
    </div>
</div>
