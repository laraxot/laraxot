# Risoluzione WCAG – Tecniche W3C e piano Theme Two

Documento di riferimento per le tecniche WCAG 2.1 studiate e per come il **tema Two** intende risolverle. Pensato per allineare più agenti sullo stesso piano di intervento.

**Riferimento operativo**: [wcag-compliance-plan.md](wcag-compliance-plan.md) (checklist, file da modificare, pattern già implementati).

---

## Riepilogo audit (occorrenze)

| Livello | Tecnica | Occorrenze |
|---------|--------|------------|
| Errore | **F96** Label in Name (aria-label ≠ testo visibile) | 85 |
| Errore | ARIA6 (aria-label per oggetti) | 26 |
| Errore | G18 (contrasto 4.5:1) | 25 |
| Errore | F78 / G195 (focus visibile) | 5 ciascuno |
| Errore | C21 (line-height in CSS) | 5 |
| Errore | H30 (scopo link) | 4 |
| Warning | C12-13-14 (font size percent/em) | 5 |
| Warning | ARIA11 (landmark regions) | 3 |
| Warning | H67 (img decorative alt vuoto) | 2 |
| Warning | G162 (posizione label form) | 1 |

---

## Tecniche W3C di riferimento

| Tecnica | Titolo | Criteri | Ruolo |
|--------|--------|---------|--------|
| [H44](https://www.w3.org/WAI/WCAG21/Techniques/html/H44) | Using label elements to associate text labels with form controls | 1.1.1, 1.3.1, 3.3.2, 4.1.2 | Sufficient |
| [H67](https://www.w3.org/WAI/WCAG21/Techniques/html/H67) | Using null alt text and no title attribute on img for decorative images | 1.1.1 | Advisory |
| [F78](https://www.w3.org/WAI/WCAG21/Techniques/failures/F78) | Failure: styling outlines/borders that remove or hide focus indicator | 2.4.7, 1.4.11 | Failure |
| [G195](https://www.w3.org/WAI/WCAG21/Techniques/general/G195) | Using an author-supplied, visible focus indicator | 2.4.7, 1.4.11 | Sufficient |
| [H30](https://www.w3.org/WAI/WCAG21/Techniques/html/H30) | Providing link text that describes the purpose of a link | 2.4.4, 2.4.9, 1.1.1 | Sufficient |
| [F96](https://www.w3.org/WAI/WCAG21/Techniques/failures/F96) | Failure: accessible name does not contain the visible label text (Label in Name) | 2.5.3 | Failure |
| [C8](https://www.w3.org/WAI/WCAG21/Techniques/css/C8) | Using CSS letter-spacing to control spacing within a word | 1.4.12, 1.3.2 | Advisory |
| [C21](https://www.w3.org/WAI/WCAG21/Techniques/css/C21) | Specifying line spacing in CSS | 1.4.12, 1.4.8 | Sufficient |
| [C38](https://www.w3.org/WAI/WCAG21/Techniques/css/C38) | Using CSS width, max-width and flexbox to fit labels and inputs | 1.4.10 Reflow | Sufficient |
| C12-13-14 | Using percent, em or named font sizes (resize text) | 1.4.4, 1.4.5 | Advisory |
| [G18](https://www.w3.org/WAI/WCAG21/Techniques/general/G18) | Ensuring contrast ratio ≥ 4.5:1 (text/images of text vs background) | 1.4.3, 1.4.6 | Sufficient |
| [G162](https://www.w3.org/WAI/WCAG21/Techniques/general/G162) | Positioning labels to maximize predictability of relationships | 1.3.1, 3.3.2, 2.5.3 | Sufficient/Advisory |
| [H98](https://www.w3.org/WAI/WCAG21/Techniques/html/H98) | Using HTML autocomplete attributes | 1.3.5 Identify Input Purpose | Sufficient |
| [ARIA6](https://www.w3.org/WAI/WCAG21/Techniques/aria/ARIA6) | Using aria-label to provide labels for objects | 1.1.1 Non-text Content | Sufficient |
| [ARIA11](https://www.w3.org/WAI/WCAG21/Techniques/aria/ARIA11) | Using ARIA landmarks to identify regions of a page | 1.3.1, 2.4.1 | Sufficient |

---

## Come il tema Two risolve ogni tecnica

### H44 – Label associati ai controlli

- **Regola**: `<label for="id">` con `id` univoco su input/select/textarea; per checkbox/radio il `<label>` va **dopo** l’input; label visibile per 3.3.2.
- **Theme Two**: Pattern in [wcag-compliance-plan.md](wcag-compliance-plan.md) § H44. File: blog search bar, contact form, select in blocchi servizi. Verificare tutti i select/checkbox generati da Blade/Livewire.

### F78 / G195 – Focus visibile

- **F78 (da evitare)**: No `:focus { outline: none }`, no outline uguale al focus, no bordo spesso stesso colore del focus che lo nasconde.
- **G195 (da applicare)**: Indicatore di focus visibile (contrasto ≥ 3:1, spessore ≥ 2px, area almeno 1px intorno al componente).
- **Theme Two**: CSS globale in `resources/css/app.css` con `:focus-visible` (outline 3px, offset, box-shadow). Dettaglio in [wcag-compliance-plan.md](wcag-compliance-plan.md) § F78/G195. Verifica: navigazione da tastiera su header, footer, form, link.

### H30 – Scopo del link

- **Regola**: Testo del link (o `alt` dell’immagine se è l’unico contenuto del link) che descrive la destinazione; se link ha testo + immagine e il testo basta, `alt` può essere vuoto.
- **Theme Two**: Link icon-only con `aria-label`; link con testo descrittivo; no `href="#"` senza scopo. Pattern e file in [wcag-compliance-plan.md](wcag-compliance-plan.md) § H30. Controllare header, footer, social, CTA.

### C8 – Letter-spacing

- **Regola**: Usare `letter-spacing` CSS invece di caratteri spazio per distanziare parole; evitare valori eccessivi che peggiorano la leggibilità.
- **Theme Two**: Rimosso `tracking-widest` dal footer; mantenere `letter-spacing` moderato. [wcag-compliance-plan.md](wcag-compliance-plan.md) § C8.

### C38 – Reflow (label e input)

- **Regola**: Label e input che si adattano senza scroll orizzontale a 320px e zoom 400%; flexbox, `width`/`max-width`, wrap.
- **Theme Two**: Media query 320px, `flex-wrap`, `max-width: 100%` su input/select/textarea in `app.css`. [wcag-compliance-plan.md](wcag-compliance-plan.md) § C38. Test: viewport 320px e zoom 400%.

### G18 – Contrasto

- **Regola**: Contrasto ≥ 4.5:1 per testo normale (≥ 3:1 per testo grande); formula luminance WCAG.
- **Theme Two**: Override in `app.css` per classi footer (`text-white/95` ecc.); sostituzioni da `text-blue-200` a bianco. [wcag-compliance-plan.md](wcag-compliance-plan.md) § G18. Verificare con strumenti (WAVE, axe, CCA).

### H98 – Autocomplete

- **Regola**: Attributo `autocomplete` con token standard (given-name, family-name, email, tel, ecc.) sui campi che raccolgono dati dell’utente.
- **Theme Two**: Contact form e altri form con `autocomplete` appropriato; select di ricerca con `autocomplete="off"`. [wcag-compliance-plan.md](wcag-compliance-plan.md) § H98. Estendere a tutti i form che chiedono dati utente.

### ARIA6 – aria-label

- **Regola**: `aria-label` per dare un nome a oggetti (pulsanti, landmark) quando non c’è testo visibile; non usarlo dove già esiste un label nativo appropriato (es. `<label for>`).
- **Theme Two**: `aria-label` su bottoni solo icona e link solo icona; `aria-hidden="true"` su icone decorative; preferire `<label for>` sugli input. [wcag-compliance-plan.md](wcag-compliance-plan.md) § ARIA6.

### F96 – Label in Name (priorità alta, 85 occorrenze)

- **Regola**: Il nome accessibile (aria-label / aria-labelledby / testo visibile) deve **contenere** il testo visibile del controllo. Utenti voice/speech dicono il testo visibile; se aria-label è diverso (es. bottone "Go" con aria-label="Cerca nel sito") il comando fallisce.
- **Theme Two**: Dove c’è testo visibile su link/bottoni, **non** sovrascrivere con aria-label diverso; oppure fare in modo che aria-label **includa** la stringa visibile (es. aria-label="Cerca: vai" se il bottone dice "Vai"). [wcag-compliance-plan.md](wcag-compliance-plan.md) § F96.

### H67 – Immagini decorative

- **Regola**: Immagini puramente decorative: usare `alt=""` e **non** `title`; così le AT le ignorano.
- **Theme Two**: Verificare tutte le `<img>` decorative (icone, bordi, placeholder): `alt=""`. Non usare `alt=" "` o alt assente. [wcag-compliance-plan.md](wcag-compliance-plan.md) § H67.

### ARIA11 – Landmark regions

- **Regola**: Usare landmark ARIA (role="banner", "main", "navigation", "contentinfo", "complementary", "search", "form") per identificare le regioni; landmark multipli dello stesso tipo devono avere nome accessibile univoco (aria-label o aria-labelledby).
- **Theme Two**: Header → banner; nav → navigation (con nome se più di uno); contenuto principale → main; footer → contentinfo; form di ricerca → search. [wcag-compliance-plan.md](wcag-compliance-plan.md) § ARIA11.

### G162 – Posizione label nei form

- **Regola**: Label **prima** del campo (sopra o a sinistra); per checkbox/radio label **dopo** il controllo.
- **Theme Two**: Form contatti e altri: label sopra/sinistra degli input; checkbox/radio con label a destra. [wcag-compliance-plan.md](wcag-compliance-plan.md) § G162.

### C12-13-14 – Dimensioni font (resize text)

- **Regola**: Usare unità relative (%, em, rem) o dimensioni named per il font, così l’utente può ridimensionare il testo (zoom / impostazioni).
- **Theme Two**: Verificare che non ci siano solo px per font-size in blocchi di testo; preferire rem/em. [wcag-compliance-plan.md](wcag-compliance-plan.md) § C12-13-14.

### C21 – Line spacing (line-height)

- **Regola**: Specificare `line-height` in CSS (tra 1.5 e 2 per blocchi di testo) per 1.4.12 Text Spacing.
- **Theme Two**: In `app.css` o utility Tailwind, assicurare `line-height` ≥ 1.5 per paragrafi e contenuto testuale. [wcag-compliance-plan.md](wcag-compliance-plan.md) § C21.

---

## Ordine di intervento suggerito (multi‑agente)

Per evitare sovrapposizioni se più agenti lavorano sullo stesso prompt:

1. **F96** (85 occ): Label in Name – verificare tutti gli elementi con aria-label/aria-labelledby; il nome accessibile deve contenere il testo visibile. Priorità massima.
2. **H44 + H98 + G162**: form (label associati + autocomplete + posizione label: prima del campo, dopo checkbox/radio).
3. **G18 + C8 + C21 + C12-13-14**: CSS – contrasto, letter-spacing, line-height (1.5–2), font size in rem/em.
4. **F78/G195**: focus visibile – `app.css` e componenti interattivi.
5. **H30 + ARIA6**: link e aria – testo descrittivo; aria-label solo dove non c’è testo visibile e deve includere/coincidere con eventuale testo (F96).
6. **H67**: img decorative – `alt=""` senza title.
7. **ARIA11**: landmark – banner, main, navigation, contentinfo, search; nomi univoci se landmark duplicati.
8. **C38**: reflow – 320px, zoom 400%.

Ogni agente può dichiarare nel commit o nell’issue su quale tecnica/insieme di file sta lavorando.

---

## Collegamenti

- [Piano WCAG Theme Two](wcag-compliance-plan.md)
- [Validazione accessibilità](validation-in-depth.md)
- [W3C WCAG 2.1 Techniques](https://www.w3.org/WAI/WCAG21/Techniques/)
- [Understanding WCAG](https://www.w3.org/WAI/WCAG21/Understanding/)
