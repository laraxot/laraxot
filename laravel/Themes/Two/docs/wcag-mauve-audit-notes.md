# Note audit MAUVE++ – Tema Two

Riferimento: report MAUVE++ su https://sottana.net (WCAG 2.1 AA, viewport desktop).

## Correzioni applicate nel tema

### F96 – Label in Name (2.5.3)
- **Header**: Pulsante lingua – `aria-label="Cambia lingua (attuale: {{ $currentLocale }})"` così il nome accessibile contiene il testo visibile (es. "it").
- **Footer**: Link social – `aria-label` descrittivi tipo "Apri profilo LinkedIn (si apre in nuova scheda)" / "Seguici su LinkedIn (si apre in una nuova finestra)" per H30 e voice.
- Link mappa: `aria-label` con indirizzo e testo sr-only per H30.

### ARIA11 – Landmark
- Header: `role="banner"` (già in header v1).
- Footer: `role="contentinfo"` (già in footer v1).
- Contenuto principale: `role="main"` e `id="main-content"` in `layouts/app.blade.php` e in `components/layouts/main.blade.php` (con skip link "Vai al contenuto principale").

### G18 – Contrasto
- Override in `resources/css/app.css` per `.text-blue-200`, `.text-blue-100/*`, `.text-gray-200` su sfondo scuro.
- **Footer v1 (2026-02)**: testo su sfondo gradient blu reso conforme WCAG AA 4.5:1: `text-white` per titoli e sottotitolo brand, `text-gray-100` per descrizioni/paragrafi/barra bassa; titolo "Normative & Certificazioni" e servizi/contatti in `text-white` o `text-gray-100`. Icona back-to-top `text-gray-200` su `#0F3460`.
- Override per `[role="contentinfo"]` dove necessario.

### H30 – Scopo link
- Link mappa footer: `aria-label` descrittivo + `<span class="sr-only">Apri mappa della nostra sede su OpenStreetMap</span>`.

### ARIA6 – aria-label e icone
- SVGs decorativi: `aria-hidden="true"` (già presente su logo, icone CTA, hamburger, social, contatti, back to top in header v1 e footer v1).

## Cookie consent (vendor)

Gli errori/warning su **cookie-consent** (F78, G195, C12, C21, G162) riguardano il pacchetto `vendor/cookie-consent`. Il tema Two non può modificarne il markup. Possibili azioni:
- Override CSS nel tema per focus visibile (`.lcc-button:focus-visible`) e font-size/line-height.
- Issue o PR al repository del pacchetto cookie-consent.
- Documentare in [wcag-compliance-plan.md](wcag-compliance-plan.md) come “esterno al tema”.

## Layout e landmark main (aggiornato 2026-02)

- **Struttura**: header e footer fuori da `<main>` (slot `beforeMain` / `afterMain` in `app.blade.php` e `main.blade.php`). Ordine: skip link, header, main, footer.
- **`components/layouts/app.blade.php`**: usa `beforeMain` e `afterMain` per header e footer; slot default = solo contenuto pagina.
- **`components/layouts/main.blade.php`**: renderizza `beforeMain`, poi `<main id="main-content" role="main">` (solo slot default), poi `afterMain`; skip link a #main-content. Attenzione: se questo layout riceve come slot l’intero corpo (header + content + footer), il landmark main avvolge troppo; in quel caso la struttura andrebbe separata (header fuori da main, main solo sul contenuto centrale, footer fuori).

## PageSpeed Insights – correzioni applicate

Riferimento: report PageSpeed (mobile) su https://sottana.net/it.

### Accessibilità (contrasto)
- **Testo arancione su sfondo chiaro**: in `app.css` è stato aggiunto override per `#main-content .text-brand-orange` e `main .text-brand-orange` con `var(--color-brand-orange-dark)` (WCAG AA 4.5:1).
- **Sezione "Cosa Controlliamo?"**: il componente `what-we-do/checklist` usa già `h3` per "Perché è fondamentale?" e `text-brand-orange-dark` per il callout.

### Best practice – errori console
- **ReferenceError $isActive**: nel menu mobile (header v1) non viene usato alcun `:class` Alpine; le classi attivo/non attivo sono solo server-side (`class="{{ $isActive ? '...' : '...' }}"`). Dopo il deploy l’errore scompare.
- **404 immagini**: path hero e servizi/sectors aggiornati da `/themes/Two/resources/images/` e `/themes/Two/Main_files/images/` a `/themes/Two/images/`. Hero about usa `asset('themes/Two/images/hero-bg.jpg')` come default. I blocchi services/grid, content/split e sectors/split normalizzano gli URL che iniziano con `/themes/Two/` tramite `asset()` per la base corretta. **Deploy**: copiare le immagini (es. da `Main_files/images/` o `resources/images/`) in `public/themes/Two/images/` (hero-bg.jpg, medical-equipment.jpg, veterinary-radiology.jpg) perché siano servite.

### Link social e intestazioni
- Link social footer: già con `aria-label` univoci (LinkedIn, Facebook, Instagram). Contrasto footer gestito in app.css.
- Ordine intestazioni: checklist usa h2 (titolo sezione) → h3 (callout "Perché è fondamentale?") → h3 (titoli card).

## Elenco esiti MAUVE++ (riferimento)

| Criterio | Tech | Tipo | Occ. | Azione tema |
|----------|------|------|------|-------------|
| 1.1.1 | H67 | Warning | 2 | Immagini che AT deve ignorare: `alt=""` senza title. Corretto: `home/article.blade.php` (img con alt=""), `blocks/image.blade.php` (alt sempre presente, vuoto se non fornito). |
| 1.3.1, 2.4.1 | ARIA11 | Warning | 3 | Landmark: header (banner), main (id main-content), footer (contentinfo) già in layout; slot beforeMain/afterMain. |
| 1.3.1, 3.3.2 | G162 | Warning | 1 | Label vicino/associato al controllo (form). Verificare singolo caso in form contatti/servizi. |
| 1.4.4, 1.4.5 | C12-13-14 | Warning | 5 | Font size: `html { font-size: 100% }` in app.css; cookie consent in vendor (override non possibile). |
| 2.5.3 | F96 | Warning | 85 | Ogni `aria-label`/`aria-labelledby` deve corrispondere al nome visibile. Audit puntuale; header lingua e link social già allineati. |
| 1.1.1 | ARIA6 | Error | 26 | Oggetti con etichetta: icone/SVG decorativi `aria-hidden="true"`; nome accessibile sul controllo (link/button). Header e footer v1 già conformi. |
| 1.4.3 | G18 | Error | 25 | Contrasto ≥4.5:1. Footer v1 e override app.css (text-white, text-gray-100, brand-orange-dark) applicati; altri blocchi da verificare. |
| 1.4.11, 2.4.7 | G195, F78 | Error | 5 ciascuno | Focus visibile: `:focus-visible` in app.css per a, button, input, select, textarea; override `.lcc-button:focus-visible` per cookie consent. |
| 1.4.12 | C21 | Error | 5 | Line-height: body, p, li, label, .lcc-modal in app.css (1.5); cookie consent da vendor. |
| 2.4.4 | H30 | Error | 4 | Testo link descrittivo o aria-label. Corretto: home/article.blade.php (aria-label su link immagine, categoria, articolo, autore, anteprima); footer/header già con aria-label su link social e CTA. |

## Correzioni 2026-02 (H67, alt obbligatorio, H30)

- **home/article.blade.php**: img con `alt=""`; link immagine con `aria-label="Vai all'articolo: ..."`; link categoria/articolo/autore/anteprima con aria-label descrittivo; icona freccia `aria-hidden="true"`.
- **components/blocks/image.blade.php**: attributo `alt` sempre emesso (`alt="{{ $alt ?: '' }}"`) per evitare img senza alt (H67/ARIA6).

## Verifica post-modifiche

Dopo deploy, rieseguire MAUVE++ su:
- Home
- Una pagina con form (contatti)
- Una pagina con footer completo (social, mappa)

e confrontare con [wcag-audit-checklist.md](wcag-audit-checklist.md).
