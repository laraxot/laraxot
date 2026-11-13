---
title: "Footer partner / sponsorship — decisione marketing"
type: concept
status: decision-input
updated: 2026-10-15
tags: [footer, marketing, sponsorship, bmad, sixteen]
qmd: "footer: partner sì, banner no sixteen"
---

# Footer: partner sì, banner no

## Perché esiste questo concetto

Valutare se aggiungere nel footer «Sostieni il nostro progetto» e una fila di
banner sponsor. La risposta di prodotto (solo BMAD, nessuna implementazione) è
nel dossier root.

## Verdict

- **Sì** a una sezione trasparente «Partner e sostenitori» (loghi statici,
  disclosure, no tracking), quando policy e tenant lo permettono.
- **No** (primo rilascio) a banner commerciali in linea nel chrome istituzionale.
- **CTA «Sostieni»** solo dopo beneficiario legale, pagamento, privacy e
  rendicontazione; preferibile un link a pagina dedicata.

## Perché (business logic)

Il footer è spazio istituzionale del servizio di segnalazione. Fiducia e
neutralità battano inventorio pubblicitario. WCAG/reflow, privacy e multi-tenant
rendono i banner costosi e rischiosi rispetto a grant, adozione Comuni e
recognition partner.

## Dossier SSoT

- Sostenibilità (canali): [project-sustainability](../../../../../../bmad-output/project-sustainability/README.md)
- FO Sixteen: [project-sustainability-front-office.md](../../project-sustainability-front-office.md)
- Footer partner: [footer-sponsorship](../../../../../../bmad-output/footer-sponsorship/README.md)
- Mappa modelli (ponte): [how-to-sustain.md](../../../../../../bmad-output/footer-sponsorship/how-to-sustain.md)
- Tema UX: [sustainability-and-footer.md](../../bmad/sustainability-and-footer.md)
- Story: [footer-sponsor-marketing.story.md](../../bmad/stories/footer-sponsor-marketing.story.md)

## Relazione con footer JSON SSoT

Il contratto runtime attuale resta [DCP-222](../../bmad/stories/dcp-222-footer-json-ssot-vestito.story.md):
JSON = contenuto, Blade = vestito. Questa iniziativa **non** modifica ancora
`footer.json`; ogni futuro blocco partner deve entrare come proiezione CMS dopo
il GO di governance.
