# Architettura — nomi file CSS solo in inglese

**Riferimento esterno (commenti):** [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche)  
**Slug interno tema:** `civic-design-*` (template PA), non `design-comuni-*`

---

## Religione

Il codice tecnico è **language-agnostic**. L’italiano è solo in `lang/`, copy UI e slug route pubblici.

| Layer | Esempio |
|-------|---------|
| Filename CSS | `ticket-parity.css`, `topics-parity.css` |
| Dominio Laravel | `Ticket`, `BuildTicket*` |
| UI italiana | `lang/it/ticket.php` → label «segnalazione» |

**Zen:** il path del file non traduce — traduce solo `lang/`.

---

## Mapping canonico (`app.css`)

| File | Ruolo |
|------|--------|
| `ticket-parity.css` | Parity pagine ticket / wizard FO |
| `topics-parity.css` | Parity argomenti (pagina `data-page="argomenti"`) |
| `civic-design-global.css` | Layer globale kit PA |
| `civic-design-visual-fix.css` | Fix visivi cross-page |
| `civic-design-global-fixes.css` | Override finali |
| `components/civic-design-tokens.css` | Token `--fixcity-*`, `--dc-*` |
| `services-parity-fix.css` | Parity servizi |
| `administration-parity-fix.css` | Parity amministrazione |

---

## Verifica

```bash
find laravel/Themes/Sixteen/resources/css -maxdepth 1 -name '*segnalazi*' -o -name '*argomenti*' -o -name '*comuni*' -o -name '*servizi*' -o -name '*amministrazione*'
# atteso: nessun match (ecc. debt documentato in ADR)
bash bashscripts/ai/check-italian-names-in-code.sh
```

---

## Collegamenti

- [ADR css-filenames](../../../../docs/wiki/decisions/css-filenames-english-no-italian.md)
- [Wiki concept](../wiki/concepts/css-filename-english-naming.md)
- [no-italian-component-names](../wiki/rules/no-italian-component-names.md)
- [design-comuni-site-wide-component-css-rule](../wiki/concepts/design-comuni-site-wide-component-css-rule.md)
