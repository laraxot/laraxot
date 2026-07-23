# Main Files Organization Rule

## 🔴 CRITICAL RULE

**Tutti i file HTML scaricati dal sito target DEVONO essere salvati nella directory `laravel/Themes/Two/Main_files/`, NON nella root di `laravel`.**

## Perché questa regola?

1. **Separazione chiara**: La directory `Main_files` è dedicata esclusivamente ai file di riferimento per replicare il sito target. Questo mantiene separato il codice dell'applicazione dai file di riferimento.

2. **Organizzazione pulita**: Tutti i file HTML, CSS, JS e assets scaricati dal sito target sono in un unico luogo ben definito.

3. **Manutenibilità**: Facilita la ricerca e l'aggiornamento dei file di riferimento senza "sporcare" la root del progetto.

4. **Consistenza**: Tutti gli agenti AI e gli sviluppatori sanno dove cercare i file di riferimento.

## Naming Convention

I file devono essere nominati con pattern consistente:

- `target-home.html` - Homepage del sito target
- `target-about.html` - Pagina Chi Siamo del sito target
- `target-services.html` - Pagina Servizi del sito target
- `target-blog.html` - Pagina Blog del sito target
- `target-faq.html` - Pagina FAQ del sito target
- `target-contacts.html` - Pagina Contatti del sito target

## Sottocartelle per organizzazione

### Images
```
Main_files/
├── images/
│   ├── blog/
│   │   ├── dlgs-101-2020.jpg
│   │   ├── manutenzione-elettromedicali.jpg
│   │   └── radioprotezione-2026.jpg
│   ├── hero-bg.jpg
│   ├── medical-equipment.jpg
│   ├── sector-dental.jpg
│   ├── sector-veterinary.jpg
│   └── testimonials/
│       ├── dr-roberto-magni.jpg
│       ├── dr-elena-visentin.jpg
│       ├── dr-paolo-verdi.jpg
│       └── dr-giulia-bianchi.jpg
```

### Assets (CSS/JS)
```
Main_files/
├── lightseagreen-dogfish-560272.hostingersite.com/
│   ├── assets/
│   │   ├── index-17d8d2a5.css
│   │   └── index-8281de30.js
│   ├── images/
│   ├── robots.txt
│   └── index.html
```

### Snippets
```
Main_files/
├── js_snippet.txt
├── root_innerHTML.txt
└── target-site-structure.html
```

## Struttura Completa Esempio

```
laravel/Themes/Two/Main_files/
├── .gitkeep
├── about-target.html
├── blog-target.html
├── contacts-target.html
├── faq-target.html
├── homepage.html
├── index.html
├── services-target.html
├── target-about.html
├── target-blog.html
├── target-chi-siamo.html
├── target-contatti.html
├── target-faq.html
├── target-home.html
├── target-servizi.html
├── target-site-full.html
├── target-site-structure.html
├── target-site.html
├── target_header.html
├── js_snippet.txt
├── root_innerHTML.txt
├── index-17d8d2a5.css
├── index-8281de30.js
├── images/
│   ├── blog/
│   ├── hero-bg.jpg
│   ├── medical-equipment.jpg
│   ├── sector-dental.jpg
│   ├── sector-veterinary.jpg
│   └── testimonials/
├── lightseagreen-dogfish-560272.hostingersite.com/
│   ├── assets/
│   ├── images/
│   ├── robots.txt
│   └── index.html
└── reference-site/
    └── header.html
```

## Comandi Git

Quando si aggiungono file target, usare:

```bash
cd laravel/Themes/Two
git add Main_files/
git commit -m "docs: add target site reference files"
git push
```

## Regole per gli Agenti AI

1. **SEMPRE** salvare i file HTML scaricati in `Main_files/`
2. **MAI** salvare file target nella root di `laravel`
3. Usare naming convention consistente `target-[pagina].html`
4. Organizzare immagini in sottocartelle `images/`
5. Aggiungere `.gitkeep` per mantenere directory vuote in git
6. Documentare la struttura nel README se necessario

## Vantaggi

✅ Separazione pulita tra codice e riferimenti
✅ Facile manutenzione e aggiornamento
✅ Consistenza tra tutti gli agenti AI
✅ Organizzazione logica dei file
✅ Migliore tracciabilità dei file di riferimento

## Memorizza questa regola!

Questa regola deve essere memorizzata da tutti gli agenti AI per garantire l'organizzazione corretta del progetto.

**Ricorda**: `Main_files/` = file di riferimento target site
**Ricorda**: Root `laravel/` = codice dell'applicazione

---

*Creato il 2026-02-07*
*Aggiornato da: iFlow CLI*