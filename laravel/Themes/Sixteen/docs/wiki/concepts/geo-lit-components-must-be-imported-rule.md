---
name: geo-lit-components-must-be-imported-rule
description: "OGNI componente Lit nel modulo Geo deve essere importato in Themes/Sixteen/resources/js/app.js — altrimenti il browser non riconosce il custom element"
type: rule
---

# Geo Lit Components Must Be Imported Rule

## REGOLA PERMANENTE: Custom element Lit del modulo Geo → import obbligatorio in app.js

### Vincolo assoluto

```
OGNI nuovo componente Lit in Modules/Geo/resources/js/components/:
  1. DEVE essere importato in Themes/Sixteen/resources/js/app.js
  2. DEVE avere la chiamata customElements.define() nel file stesso
  3. DEVE essere incluso nel build del tema (npm run build)
```

### Pattern standard

```js
// In Themes/Sixteen/resources/js/app.js
import '@modules/Geo/resources/js/components/<nome-componente>-lit.js';
```

```js
// In Modules/Geo/resources/js/components/<nome-componente>-lit.js
if (!customElements.get('<nome-componente>')) {
    customElements.define('<nome-componente>', <NomeClasse>);
}
```

### Perché

Senza l'import in `app.js`, il componente JS non entra nel bundle Vite → il browser vede `<nome-componente-lit>` come HTML tag sconosciuto → non renderizza nulla → nessuna mappa visibile.

### Verifica rapida

```bash
# 1. Il componente è importato?
grep "nome-componente-lit" Themes/Sixteen/resources/js/app.js

# 2. Il browser lo riconosce?
# In browser console: customElements.get('nome-componente-lit') → NON deve essere undefined

# 3. Il file JS esiste?
ls Modules/Geo/resources/js/components/nome-componente-lit.js
```

### Checklist quando si crea un nuovo Geo Lit component

- [ ] File JS in `Modules/Geo/resources/js/components/<nome>.js`
- [ ] `customElements.define()` nel file JS
- [ ] Import in `Themes/Sixteen/resources/js/app.js`
- [ ] `npm run build` dalla cartella `Themes/Sixteen`
- [ ] Verifica `customElements.get()` in browser console

### Anti-pattern

- Creare il file JS ma non importarlo → mappa invisibile
- Importare ma non fare build → browser ha vecchi asset
- Affidarsi al fatto che "il file esiste" → il file deve essere nel bundle
