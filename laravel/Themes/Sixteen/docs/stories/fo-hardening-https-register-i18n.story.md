# FO: HTTPS di sviluppo, registrazione, inglese, contrasto

- Stato: done (2026-10-07)
- Fase BMAD: Quick Flow, un ciclo per richiesta (investigate, fix, verifica browser reale)
- Owner: Themes/Sixteen; correlate: User, Notify, Geo, Fixcity (story proprie)

## Richieste e risultato

1. **HTTPS su `https://192.168.1.40:8080`**: la geolocalizzazione del browser richiede un contesto sicuro. `php artisan serve`
   ora gira su `127.0.0.1:8081`; `laravel/scripts/dev-https-proxy.mjs` ascolta su `192.168.1.40:8080`, serve TLS (cert autofirmato con
   SAN sull'IP, in `~/.local/share/fixcity-dev-tls`), manda l'HTTP semplice in redirect 307 a HTTPS e pubblica il certificato su
   `/fixcity-dev.crt` (da installare tra le radici attendibili di Windows per togliere l'avviso). Laravel si fida di
   `X-Forwarded-*` solo da `127.0.0.1` (`bootstrap/app.php`). Risultato: 0 URL `http://` nella pagina, asset tutti 200.
   Avvio: `cd laravel && php artisan serve --host=127.0.0.1 --port=8081 &` poi `node scripts/dev-https-proxy.mjs`.
2. **Registrazione UI/UX**: chiavi grezze, tre card annidate, bottone vuoto, campi schiacciati e tagliati su mobile.
   Cause: `x-filament::section` annidato, variabili colore Filament assenti lato FO, e la regola globale
   `body:not([data-page='auth-login']) button[type=submit].fi-btn` (11-tailwind-utility-compat.css) che forzava bianco/verde:
   ora esclude tutte le `auth-*`. `14-auth-login.css`: bottone pieno (bianco su `--bs-primary`, 5,4:1), layout mobile della
   registrazione, "Mostra password" solo icona sotto i 640px. Chiude anche il CTA del login a basso contrasto.
3. **Inglese**: `/en/auth/login` mostrava testi italiani. Audit con confronto automatico del testo it/en per pagina
   (e traduttore Laravel per le chiavi): chiavi mancanti aggiunte (User, Fixcity, tema), "Cerca"/"Contatta il comune" fissi sostituiti.
4. **Logout 405, popup marker, build tema**: vedi story User, Geo e `tickets-ux-theme-symlink-geolocation-2026-10-07`.

## Metodo di verifica ripetibile (Chrome 148 + puppeteer-core nel tema)

- Misure con `getComputedStyle` e contrasto WCAG su ogni nodo di testo; screenshot desktop 1366 e mobile 390.
- Confronto it/en: stringhe identiche tra le due lingue = non tradotte; chiavi `ns::x.y` visibili = mancanti.
- Prove con dati reali: stessa Action dell'API eseguita come proprietario (`auth()->login`) e usata come mock.

## Aperto

- Footer del tema: grande area scura vuota sotto i link (pagine corte).
- 104 chiavi `fixcity::ticket` senza `en` (quasi tutte backoffice), e de/es del tema in gran parte vuote.
- Installare `/fixcity-dev.crt` nel client per eliminare l'avviso del certificato.
