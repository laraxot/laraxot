---
type: bmad-initiative
status: completed
updated: 2026-10-17
initiative_id: kp-blog-demo-populate
summary: |
  /it/blog popolato per demo investitore. DB MariaDB (fixcity_data). 16 articoli totali (3 nuovi con factory: piazza pedonale, festa cultura, restauro fontana + commenti). DesignComuni parity applicata (card, breadcrumb, hero, argomenti correlati, footer accessibilità). Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite. BMAD + Second Brain + EEC + Filament Blueprint. MCP migliorati (.mcp.json).
demo_content:
  - Post: piazza pedonale (featured, homepage), restauro fontana, servizi digitali
  - Eventi: Festa Cultura 2026 (featured, homepage), mercato contadino, concerto orchestra
  - Commenti realistici su articoli in evidenza
  - Immagini principali via media library (SVG placeholder generato)
  - Categorie: Servizi, Cultura, Ambiente, Eventi
verification:
  local_http: 200
  local_urls:
    - http://192.168.1.40:8000/it/blog
    - http://192.168.1.40:8000/it/events
    - http://192.168.1.40:8000/it/tickets
  remote_target: https://fixcity.eu/it/blog/
  db: mariadb (non sqlite)
  article_count: 29
  ticket_count: 538
  verified: 2026-10-26
