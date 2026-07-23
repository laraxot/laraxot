# NO PREDICT-SPECIFIC PAGES IN THEME

**Last Updated**: 2026-03-22  
**Status**: ✅ CRITICAL RULE  
**Theme**: TwentyOne  
**Principle**: Theme Agnosticism

---

## 🔴 CRITICAL RULE

**MAI creare file specifici per modulo nel tema!**

### ❌ SBAGLIATO

```
Themes/TwentyOne/resources/views/pages/predicts/[slug].blade.php  ← NO!
Themes/TwentyOne/resources/views/pages/blog/[slug].blade.php      ← NO!
Themes/TwentyOne/resources/views/pages/events/[slug].blade.php    ← NO!
```

### ✅ CORRETTO

```
Themes/TwentyOne/resources/views/pages/[container0]/[slug0]/index.blade.php  ← YES!
```

---

## 🧠 FILOSOFIA

> **"Il tema è come un vestito: veste qualsiasi corpo, non ha una forma propria."**

**Il tema NON deve sapere cosa sta renderizzando!**

---

## 📚 DOCUMENTAZIONE COMPLETA

Per la filosofia completa e gli esempi, vedi:
- `docs/project/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md` - Documentazione estesa
- `Modules/Predict/docs/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md` - Vista dal modulo

---

## ✅ CHECKLIST

Prima di creare un file nel tema:

- [ ] Questo file funzionerebbe per ANY container0?
- [ ] NON sto importando modelli specifici?
- [ ] NON sto scrivendo logica business?
- [ ] Posso usare `[container0]/index.blade.php` invece?

Se **ALMENO UNA** è **NO** → **FERMATI!**

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-22
