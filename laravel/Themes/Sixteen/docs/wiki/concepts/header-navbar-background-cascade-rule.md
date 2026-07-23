# Header Navbar Background Cascade Rule

## Regola

La navbar principale dell'header Design Comuni deve avere sfondo verde coerente con la fascia logo/slogan.

Il fix deve essere site-wide sul componente header, non page-scoped:

- SI: `.it-header-navbar-wrapper`, `.it-header-navbar-wrapper .navbar`, `.it-header-navbar-wrapper .container`, `.it-header-navbar-wrapper .row`, `.it-header-navbar-wrapper .col-12`
- NO: `.page-content[data-slug="..."]`
- NO: `.ticket-wizard-root`
- NO: classi route-specific come owner del colore

## Perche l'errore si ripete

Bootstrap Italia applica background propri a piu' livelli della navbar. Cambiare solo il parent:

```css
.it-header-navbar-wrapper {
  background-color: #007a52;
}
```

non basta, perche' i figli possono coprire il parent:

```css
.it-header-navbar-wrapper .navbar { background: #06c; }
```

Il false friend e': "il wrapper e' verde, quindi la navbar e' verde". Nel browser l'utente vede il background del child, non del parent.

## Pattern corretto

L'override finale del tema deve coprire tutta la catena visuale:

```css
.it-header-navbar-wrapper,
.it-header-navbar-wrapper .container,
.it-header-navbar-wrapper .row,
.it-header-navbar-wrapper .col-12,
.it-header-navbar-wrapper .navbar {
  background: #007a52 !important;
}
```

I link devono restare trasparenti e bianchi:

```css
.it-header-navbar-wrapper .navbar-nav .nav-link {
  background: transparent !important;
  color: #ffffff !important;
}
```

## Best practice

- Verificare con Playwright `getComputedStyle()` sul nodo realmente visibile (`.navbar` e link), non solo leggendo il CSS sorgente.
- Tenere la regola alla fine del CSS tema se deve vincere su Bootstrap Italia e regole legacy.
- Documentare il motivo nel wiki locale prima di cambiare di nuovo il colore header.

## Bad practice

- Cambiare solo il wrapper.
- Aggiungere selettori per pagina.
- Usare classi del ticket wizard per correggere l'header.
- Fidarsi del parent background quando un child ha background opaco.

