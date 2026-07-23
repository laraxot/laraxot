# Contenuto per GitHub Issue – WCAG 2.1 AA Theme Two

Copia il blocco sotto nel corpo di una **nuova issue** (titolo suggerito: `WCAG 2.1 AA: piano risoluzione tecniche H44, F78, G195, H30, C8, C38, G18, H98, ARIA6`).

---

## Descrizione

Piano di risoluzione per conformità WCAG 2.1 Level AA sul frontoffice (tema Two, sottana.net). Le tecniche W3C da applicare sono state studiate e mappate su azioni concrete nel tema.

### Tecniche coinvolte

| Tecnica | Titolo | Priorità |
|--------|--------|----------|
| H44 | Label associati ai form controls | Alta |
| F78 / G195 | Focus visibile (evitare rimozione; fornire indicatore) | Alta |
| H30 | Scopo del link (testo descrittivo / aria-label) | Alta |
| G18 | Contrasto minimo 4.5:1 | Alta |
| H98 | Autocomplete su form | Media |
| ARIA6 | aria-label per oggetti senza testo | Media |
| C8 | Letter-spacing (evitare eccessi) | Bassa |
| C38 | Reflow label/input (320px, zoom 400%) | Media |

### Documentazione

- **Piano risoluzione e ordine intervento**: [wcag-techniques-resolution.md](./wcag-techniques-resolution.md)
- **Checklist e pattern implementati**: [wcag-compliance-plan.md](./wcag-compliance-plan.md)
- **Riferimenti W3C**: link alle tecniche nel doc sopra.

### Ordine di intervento (per evitare conflitti tra agenti)

1. H44 + H98 (form: label + autocomplete)
2. G18 + C8 (CSS contrasto e spacing)
3. F78 / G195 (focus)
4. H30 + ARIA6 (link e aria-label)
5. C38 (reflow)

### Accettazione

- [ ] Tutte le checklist in `wcag-compliance-plan.md` completate
- [ ] Validazione con axe-core o A11y MCP su pagine principali
- [ ] Nessun regresso su PageSpeed Accessibility
