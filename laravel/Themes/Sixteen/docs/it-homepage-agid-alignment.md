## obiettivo

Rendere la homepage localizzata `/it/` identica al riferimento AGID (Design Comuni) per struttura, gerarchie tipografiche, spaziature, colori e componenti.

Riferimento design esterno: [Design Comuni – Elenco segnalazioni](https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html)

## principi

- Usare il Design System AGID (palette, scale tipografiche, spaziature, componenti).
- Evitare stili inline: utilizzare utility Tailwind e classi del tema.
- Mantenere la compatibilità con Filament e il sistema a blocchi del tema.
- Tutti i percorsi interni nei docs devono essere relativi.

## struttura informativa richiesta

1. Top bar (lingua, accesso, switch temi/lingue)
2. Header principale con logo, titolo ente, sottotitolo/tagline
3. Barra di navigazione orizzontale con voci primarie (Amministrazione, Novità, Servizi, Vivere il Comune) + link rapidi a destra
4. Breadcrumb (1. Home / 2. Pagina corrente)
5. Titolo pagina e sottotitolo/descrizione contestuale
6. Selettori/filtri (quando applicabili) + tabs (Mappa/Elenco)
7. Lista elementi con CTA “Mostra tutto” e “Carica altre…”
8. Sezione “Fai una segnalazione” (CTA primaria)
9. Valutazione contenuto (rating) + micro‑survey
10. Footer istituzionale completo (contatti, categorie, link legali, social)

## mapping componenti tema → sezione agid

- Top bar + header + nav: `resources/views/components/sections/header.blade.php`
- Breadcrumb: componente Blade dedicato (se assente, inserirlo nel layout principale)
- Titolo pagina: slot titolo del layout di pagina
- Lista elementi: componente lista/“cards” (riutilizzabile in `components/blocks`)
- CTA primarie: classi `.fi-btn-primary` o `.agid-*` già fornite dal tema
- Footer: `resources/views/components/sections/footer.blade.php` (se mancante, crearne uno coerente)

## classi e variabili da usare

- Variabili CSS: `--agid-primary`, `--agid-*`, `--italia-*-*` (vedi `resources/css/agid-colors.css` e `resources/css/app.css`)
- Tipografia: famiglia `Titillium Web`; scala: `text-sm|base|lg|xl|2xl` coerente col reference
- Spaziature: usare `max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8` per il container centrale
- Bottoni: `.fi-btn-primary`, `.fi-btn-secondary`, `.agid-focus`, `.agid-transition`
- Navigazione: mantenere contrasto alto, hover con `hover:bg-white hover:bg-opacity-10`

## implementazione /it

1) Rotta e layout
- Assicurarsi che la rotta `/it/` usi il layout principale del tema.
- Inserire breadcrumb e titolo pagina nella hero iniziale.

2) Header
- Rimuovere/limitare stili inline: impostare i colori via classi/variabili.
- Applicare container centrale: `max-w-screen-xl mx-auto px-4`.
- Allineare dimensioni logo e tipografia come da reference.

3) Navigazione
- Voci principali orizzontali, bilanciate su due gruppi come nel reference.
- Mobile: pulsante hamburger con menu a scomparsa coerente.

4) Sezione contenuto
- Titolo H1 + descrizione.
- Tabs Mappa/Elenco (stato attivo con colore primario AGID).
- Cards elenco con titolo, metadati, testo, immagini, CTA “Mostra tutto”.

5) CTA “Fai una segnalazione”
- Bottone primario a piena larghezza su mobile, prominente su desktop.

6) Valutazione pagina
- Sezione rating/stelle + micro‑survey (opzionale se già presentata altrove).

7) Footer
- Blocchi categorie, contatti, link legali, social, coerenti a griglia.

## asset e build

1) Generazione asset
```bash
cd ../../Themes/Sixteen
npm run build
npm run copy
```

2) Verifica asset
- Controllare che esista `themes/Sixteen/manifest.json` e che il CSS sia in `themes/Sixteen/assets/app-*.css`.
- Le Blade del tema devono usare `@vite([...], 'themes/Sixteen')` nei layout.

## checklist conformità visuale

- Header: colori, altezze barre, spaziature, logo e testi come reference.
- Navigazione: hover, attivi, spaziature tra voci e allineamenti.
- H1 e sottotitolo: pesi e dimensioni coerenti.
- Cards elenco: bordo, shadow, spazi interni, gerarchie tipografiche.
- CTA primarie: colore, hover/focus, spessori bordo.
- Footer: griglia e link tipizzati.
- Mobile: menu, breakpoint, riduzioni tipografiche.
- Accessibilità: contrasto AA, focus ring visibile, ordine tabulazione.

## troubleshooting

- “Non cambia lo stile”: verificare che il CSS del tema sia caricato (Network tab) e che il path punti a `themes/Sixteen/assets/app-*.css`.
- “Classi purge”: accertarsi che le direttive `@source` in `resources/css/app.css` includano Blade e PHP di app/moduli.
- “Conflitti CSS di terze parti”: modulare le importazioni e preferire utility/variabili del tema.

## attività successive

- Integrare breadcrumb e sezione rating se mancanti.
- Portare i testi e le etichette in traduzioni (`lang/it`) con chiavi strutturate.
- Scrivere test di snapshot visuali/HTML minimi per header e nav.



