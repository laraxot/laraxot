---
title: "Proxy HTTPS di sviluppo e audit it/en"
type: concept
tags: [sixteen, https, proxy, geolocation, i18n, audit, puppeteer]
created: 2026-10-07
updated: 2026-10-07
qmd: "dev https proxy 8080 8081 trustProxies geolocation secure context i18n audit it en puppeteer contrasto"
issues: []
discussions: []
status: draft
---

# Proxy HTTPS di sviluppo

`php artisan serve` (server PHP built-in) e HTTP puro. Il browser nega la geolocalizzazione fuori da un contesto sicuro
(`window.isSecureContext`), quindi `http://192.168.1.40:8080` non funziona mentre `localhost` e HTTPS si.

- `laravel/scripts/dev-https-proxy.mjs [ascolto=192.168.1.40:8080] [upstream=127.0.0.1:8081]`: sul primo byte (0x16 = TLS)
  sceglie HTTPS o HTTP; HTTP -> 307 a HTTPS; `/fixcity-dev.crt` scarica il certificato; aggiunge `X-Forwarded-Proto/For/Port`.
- `bootstrap/app.php`: `$middleware->trustProxies(at: '127.0.0.1')`. **Non** chiamare `Request::setTrustedProxies` in cima:
  il middleware `TrustProxies` lo azzera a ogni richiesta (prova: 25 URL `http://` nella pagina).
- Cert: `openssl req -x509 ... -addext subjectAltName=IP:192.168.1.40,DNS:localhost,DNS:fixcity.local`, 825 giorni, chiave 0600.
- Se la pagina e in cache di rotta/Folio (`bootstrap/cache/routes-v7.php`, `folio-routes.php`) le pagine nuove non compaiono:
  `php artisan route:clear` e cancellare `folio-routes.php`.

# Audit di traduzione it/en

1. Per ogni pagina pubblica, estrarre i testi (nodi di testo, `placeholder`, `title`, `aria-label`, `alt`) in `/it` ed `/en`.
2. Stringhe uguali nelle due lingue = non tradotte (escludere marchi e parole identiche per natura).
3. Chiavi grezze (`user::x.y`) = chiave assente in tutte le lingue, fallback incluso.
4. Per i gruppi lang: `app('translator')->get("ns::gruppo", [], 'it', false)` contro `'en'`, appiattire e confrontare le chiavi
   (i file lang con `merge_translation_files()` non si possono includere a mano).
5. Aggiungere le chiavi alla fonte (file `lang/en/...`, per Fixcity un file `ticket_*.php` unito da `ticket.php`).

# Popup e contrasto

Misurare, non indovinare: `getComputedStyle` su ogni nodo di testo contro lo sfondo effettivo risale la catena fino a un alpha > 0,5
(attenzione a `color(srgb ...)`). Per i marker usare dati reali: eseguire `BuildTicketsGeoJsonAction` come proprietario.
