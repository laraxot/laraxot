import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const mapElement = document.getElementById('map');

if (mapElement) {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const map = L.map('map', {
        zoomAnimation: !prefersReducedMotion,
        markerZoomAnimation: !prefersReducedMotion,
        fadeAnimation: !prefersReducedMotion,
    }).setView([43.7696, 11.2558], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    L.marker([43.7696, 11.2558])
        .addTo(map)
        .bindPopup('<b>Segnalazione</b><br>Esempio segnalazione')
        .openPopup();
}
