{{--
  Resa e comportamento condivisi dalle pagine di accesso (DCP-290): login, registrazione, password dimenticata,
  nuova password. Si include una volta sola dentro x-auth.shell.

  Perche' inline: lo sheet compilato non dipinge dentro `.page-content[data-dc]` e Design Comuni azzera bordi e
  aspetto dei campi con `!important`, quindi le regole che devono vincere lo ripetono.
  Livello unico dei campi: bordo 1px #5b6f82 (3:1 minimo sul bianco, WCAG 1.4.11), angoli 0.25rem, altezza 2.75rem,
  sia per i campi Filament del login sia per i campi `x-auth.field` della registrazione e del recupero.
--}}
@once
<style data-auth-style>
    .auth-login-shell { background: #f5f7f9; padding: 1.5rem 1rem 7rem; }
    .auth-login-shell__inner { width: 100%; max-width: 36rem; margin-left: auto; margin-right: auto; }
    .auth-login-shell__crumb { display: flex; flex-wrap: wrap; align-items: center; gap: 0.35rem; margin: 0 0 1rem; padding: 0; list-style: none; font-size: 0.875rem; color: #5c6f82; }
    .auth-login-shell__crumb [aria-current="page"] { color: #1a1a1a; font-weight: 600; }
    .auth-login-shell__crumb a,
    .auth-login-card .auth-login-account a,
    .auth-login-card .auth-login-links a,
    .auth-login-card form a { color: #007a52; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .auth-login-shell__crumb a:focus-visible,
<<<<<<< HEAD
    .auth-login-card a:focus-visible,
    .auth-login-card .auth-login-links button.auth-link-button:focus-visible { outline: 2px solid #007a52; outline-offset: 2px; }
    /* Azioni secondarie che non sono link (resend verifica email, logout da verify): stesso aspetto dei link. */
    .auth-login-card .auth-login-links button.auth-link-button { padding: 0; border: 0; background: none; font: inherit; color: #007a52; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; cursor: pointer; }
    .auth-link-button__loading { display: none; align-items: center; gap: 0.375rem; }
    .auth-link-button--loading .auth-link-button__label { display: none; }
    .auth-link-button--loading .auth-link-button__loading { display: inline-flex; }
    .auth-link-button:disabled { cursor: wait; opacity: 0.7; }
    /* Il CSS di Filament non e' caricato nel front office: l'SVG dell'indicatore non ha ne' misura ne' animazione (misurato 59x59 e fermo). */
    .auth-link-button__spinner { flex: none; width: 0.875rem; height: 0.875rem; transform-origin: center; animation: auth-link-spin 0.8s linear infinite; }
    @keyframes auth-link-spin { to { transform: rotate(360deg); } }
    /* Con "riduci animazioni" il movimento resta, piu' lento: e' l'unico segnale che l'invio e' in corso. */
    @media (prefers-reduced-motion: reduce) { .auth-link-button__spinner { animation-duration: 2.4s; } }
=======
    .auth-login-card a:focus-visible { outline: 2px solid #007a52; outline-offset: 2px; }
>>>>>>> f08c6884 (.)
    /* Niente pannello con bordo: le pagine di accesso, registrazione e nuova password hanno la stessa resa delle altre pagine
       (titolo allineato al breadcrumb, contenuto sullo sfondo della pagina). Il riquadro c'era solo qui: incoerente. */
    .auth-login-card { background: transparent; border: 0; border-radius: 0; padding: 1rem 0 2.5rem; }
    .auth-login-card h1 { margin: 0; font-size: clamp(1.75rem, 4vw, 2.25rem); line-height: 1.15; font-weight: 700; color: #1a1a1a; }
    .auth-login-card .auth-login-lede { margin: 0.5rem 0 0; color: #5c6f82; font-size: 1.0625rem; line-height: 1.55; }
    .auth-login-card .auth-login-account,
    .auth-login-card .auth-login-links { margin: 0.75rem 0 0; font-size: 0.9375rem; color: #1a1a1a; }
    .auth-login-card .auth-login-widget { margin-top: 1.75rem; }

    /* Riepilogo errori (x-auth.error-summary): un solo avviso role="alert" con il conteggio e tutti i messaggi.
       Colore #a50021 su #fdf2f4: 7,2:1. Il login lo usa anche per l'esito negativo e per Google annullato/rifiutato. */
    .auth-login-card .auth-login-alert { display: flex; align-items: flex-start; gap: 0.75rem; margin: 0 0 1.25rem; padding: 0.75rem 1rem; border: 1px solid #d9364f; border-left-width: 4px; background: #fdf2f4; color: #a50021; font-size: 0.9375rem; line-height: 1.5; }
    .auth-login-card .auth-login-alert__icon { flex: 0 0 auto; width: 1.25rem; height: 1.25rem; margin-top: 0.125rem; }
    /* overflow-wrap: la guida di DCP-271 contiene URL lunghi (console Google, back office) che a 375px uscivano dal riquadro. */
    .auth-login-card .auth-login-alert__text { margin: 0 !important; min-width: 0; overflow-wrap: anywhere; }
    .auth-login-card .auth-summary__title { margin: 0; font-weight: 700; }
    .auth-login-card .auth-summary__list { margin: 0.35rem 0 0; padding-left: 1.25rem; }
    .auth-login-card .auth-summary__list li { margin: 0.15rem 0; }
    /* Esito neutro o positivo (<div role="status">): #0b5e3a su #eef8f2, 7,6:1. */
    .auth-login-card .auth-status { margin: 0 0 1.25rem; padding: 0.75rem 1rem; border: 1px solid #2e8b5b; border-left-width: 4px; background: #eef8f2; color: #0b5e3a; font-size: 0.9375rem; line-height: 1.5; }

    /* Campi: login (Filament) e x-auth.field hanno lo stesso aspetto. */
    .auth-login-card form input[type="email"],
    .auth-login-card form input[type="password"],
    .auth-login-card form input[type="text"] { display: block !important; width: 100% !important; box-sizing: border-box !important; min-height: 2.75rem !important; margin-top: 0.35rem; padding: 0.5rem 0.75rem !important; border: 1px solid #5b6f82 !important; border-radius: 0.25rem !important; background: #fff !important; color: #1a1a1a !important; font-size: 1rem !important; box-shadow: none !important; }
    .auth-login-card form input[type="email"]:focus,
    .auth-login-card form input[type="password"]:focus,
    .auth-login-card form input[type="text"]:focus { outline: 2px solid #007a52; outline-offset: 1px; }
    .auth-login-card form input[aria-invalid="true"] { border-color: #a50021 !important; }
    /* Password rivelabile: l'occhio sta dentro il bordo del campo (Filament: suffix action; registrazione: x-auth.field revealable).
       Lo sheet compilato non dipinge .fi-input-wrp dentro data-dc, quindi il flex e la misura dell'icona stanno qui. */
    .auth-login-card .fi-input-wrp:has(input.fi-revealable),
    .auth-login-card .auth-field__control--reveal { display: flex; align-items: stretch; margin-top: 0.35rem; border: 1px solid #5b6f82; border-radius: 0.25rem; background: #fff; min-height: 2.75rem; }
    .auth-login-card .fi-input-wrp:has(input.fi-revealable):focus-within,
    .auth-login-card .auth-field__control--reveal:focus-within { outline: 2px solid #007a52; outline-offset: 1px; }
    .auth-login-card .fi-input-wrp:has(input[aria-invalid="true"]),
    .auth-login-card .auth-field__control--reveal:has(input[aria-invalid="true"]) { border-color: #a50021; }
    .auth-login-card .fi-input-wrp:has(input.fi-revealable) .fi-input-wrp-content-ctn,
    .auth-login-card .auth-field__control--reveal .auth-field__input-ctn { flex: 1 1 auto; min-width: 0; }
    /* `form` e `[id]` alzano la specificita' sopra la regola generica dei campi (altrimenti l'input interno tiene il suo bordo: bordo doppio). */
    .auth-login-card .fi-input-wrp:has(input.fi-revealable) input,
    .auth-login-card form .auth-field__control--reveal input[id] { margin-top: 0; border: 0 !important; min-height: 2.75rem !important; outline: 0; }
    .auth-login-card .fi-input-wrp-suffix,
    .auth-login-card .fi-input-wrp-actions { display: flex; align-items: center; flex: 0 0 auto; }
    .auth-login-card .fi-input-wrp-actions .fi-ac-btn-action,
    .auth-login-card .auth-field__toggle { display: inline-flex; align-items: center; justify-content: center; width: 2.75rem; height: 2.75rem; padding: 0; border: 0; background: transparent; color: #1a1a1a; font-size: 0; line-height: 0; cursor: pointer; }
    .auth-login-card .fi-input-wrp-actions .fi-ac-btn-action:focus-visible,
    .auth-login-card .auth-field__toggle:focus-visible { outline: 2px solid #007a52; outline-offset: -2px; }
    .auth-login-card .fi-input-wrp-actions svg,
    .auth-login-card .auth-field__toggle svg { width: 1.25rem; height: 1.25rem; flex: 0 0 auto; }

    /* x-auth.field: etichetta, aiuto, errore. */
    .auth-login-card .auth-field { display: block; width: 100%; }
    .auth-login-card .auth-field__label,
    .auth-login-card .fi-fo-field-label:not(:has(.fi-checkbox-input)) { display: block; font-size: 0.9375rem; font-weight: 600; color: #1a1a1a; }
    /* Asterisco dopo il testo, come quello di Filament nel login. */
    .auth-login-card .auth-field__req { margin-left: 0.15rem; color: inherit; font-weight: 600; }
    /* Il riquadro annidato del login (bordo e margine interno di .fo-filament-form-shell) non c'e' nella registrazione: stessa colonna piena. */
    .auth-login-card .fo-filament-form-shell { border: 0; border-radius: 0; padding: 0; background: transparent; }
    .auth-login-card .auth-field__hint { display: block; margin: 0.35rem 0 0; font-size: 0.875rem; font-weight: 400; color: #5c6f82; }
    .auth-login-card .auth-field__error { display: block; margin: 0.35rem 0 0; font-size: 0.875rem; font-weight: 400; color: #a50021; }
    .auth-login-card .auth-fields { display: flex; flex-direction: column; gap: 1.25rem; }
    .auth-login-card fieldset { min-width: 0; margin: 0; padding: 0; border: 0; }
    /* `!important`: Design Comuni dimensiona le legend a contenuto (float e larghezza) e le rende piccole. */
    .auth-login-card legend { display: block; float: none !important; width: 100% !important; margin: 0 0 1rem; padding: 0 0 0.35rem; border-bottom: 1px solid #d9e1e8; font-size: 1.125rem !important; font-weight: 700; color: #1a1a1a; }
    .auth-login-card .auth-consents { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #d9e1e8; }

    /* Checkbox (Filament `Checkbox::make('remember')` e x-auth.check).
       DC: `body:has(.page-content[data-dc]) input { appearance: none !important }` — senza
       `!important` qui il controllo resta 20×20 ma trasparente. Non ridisegnare: nativo + accent. */
    .auth-login-card .fi-fo-field-label:has(.fi-checkbox-input),
    .auth-login-card form label:has(> input[type="checkbox"]) {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.75rem;
        cursor: pointer;
    }
    .auth-login-card form label.auth-check[for] { align-items: flex-start; margin-top: 0; min-height: 2.75rem; font-size: 0.9375rem; font-weight: 400; color: #1a1a1a; }
    .auth-login-card form input[type="checkbox"],
    .auth-login-card input.fi-checkbox-input {
        appearance: auto !important;
        -webkit-appearance: checkbox !important;
        flex: 0 0 auto;
        width: 1.25rem;
        height: 1.25rem;
        margin: 0;
        accent-color: #007a52;
        cursor: pointer;
    }
    .auth-login-card form label.auth-check input[type="checkbox"] { margin-top: 0.2rem; }

    .auth-login-card form button[type="submit"] { display: flex; align-items: center; justify-content: center; width: 100%; min-height: 2.75rem; margin-top: 1.25rem; border: 0; border-radius: 0.25rem; background: #007a52; color: #fff; font-weight: 600; cursor: pointer; }
    .auth-login-card form button[type="submit"]:disabled { opacity: 0.7; cursor: progress; }
    .auth-login-card [wire\:loading] { display: none; }
    @media (min-width: 640px) {
        .auth-login-shell { padding-left: 1.5rem; padding-right: 1.5rem; }
        .auth-login-card { padding: 1.5rem 0 3rem; }
    }
</style>
{{-- Dopo un invio non riuscito il focus va al primo campo non valido (WCAG 3.3.1, 2.4.3): il riepilogo role="alert" annuncia
     cosa c'e' da correggere, il focus porta li'. Gancio sul commit di Livewire: dopo il rendering del DOM, solo per le chiamate
     a metodi (un invio), non per gli aggiornamenti dei campi. Niente `autofocus` sulle pagine: sposterebbe il focus al caricamento. --}}
<script data-auth-focus-invalid>
    (function () {
        if (window.authFocusInvalidRegistered) { return; }
        window.authFocusInvalidRegistered = true;
        var register = function () {
            window.Livewire.hook('commit', function (context) {
                if (!context.commit.calls || context.commit.calls.length === 0) { return; }
                context.succeed(function () {
                    var invalid = context.component.el.querySelector('[aria-invalid="true"]');
                    if (invalid && typeof invalid.focus === 'function') { invalid.focus(); }
                });
            });
        };
        if (window.Livewire) { register(); } else { document.addEventListener('livewire:init', register); }
    })();
</script>
@endonce
