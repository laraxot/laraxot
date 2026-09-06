---
title: "Platform — La Piattaforma di Orchestrazione"
description: "Piattaforma principale con condivisione risorse e utility centralizzate, multi-pannello"
module: "Platform"
alias: "platform"
version: "1.0.0"
priority: 200
active: true
status: "core-orchestration"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["Xot", "User", "Tenant", "UI"]
extends: []
extended_by: 0
documentation_date: "2026-05-27"
---

# Platform — La Piattaforma di Orchestrazione

## Scopo

Platform è la piattaforma principale con condivisione risorse e utility centralizzate. Ha priority 200 (la più alta tra tutti i moduli) e gestisce l'orchestrazione tra i pannelli admin: AdminPanel, OperatorPanel, CustomerPanel, SupplierPanel. È il **centro nevralgico** che coordina l'esperienza multi-ruolo del sistema.

## Religione

- **"Principio dei quattro pannelli"**: AdminPanel, OperatorPanel, CustomerPanel, SupplierPanel
- **"La piattaforma è una promessa di qualità"**: ogni modulo che su Platform gira deve rispettare il contratto di esperienza utente
- **"Multi-pannello è politica, non feature"**: la separazione dei pannelli è una decisione architetturale di business
- **"XotBase è il fondamento"**: ogni panel provider estende `XotBasePanelProvider`

## Filosofia

Platform incarna la filosofia del **ruolo-based experience design**. L'applicazione non è un monolite dove tutti vedono tutto: è un ecosistema di viste separate per ogni ruolo. Ogni pannello è un **mondo a sé stante** con le sue risorse, le sue pagine, i suoi widget.

La filosofia è **orchestrazione come servizio**: Platform non fa il lavoro dei moduli, li coordina. È il direttore d'orchestra che non suona nessuno strumento ma fa sì che tutti suonino insieme.

## Politica

- **Pannelli come contratti**: ogni panel provider deve estendere `XotBasePanelProvider`
- **Routing centralizzato**: Platform gestisce il routing tra pannelli, non i singoli moduli
- **Shared resources**: risorse condivise tra pannelli sono in Platform, non nei moduli
- **Priority 200**: Platform è l'ultimo modulo caricato
- **Event bus**: Platform coordina gli eventi cross-modulo tramite `EventServiceProvider`

## Zen

> **"La piattaforma non fa il lavoro dei singoli. Coordina."**

Lo Zen di Platform è la **armonia del sistema**. Nessun modulo è più importante di un altro, ma Platform è il glue che tiene tutto insieme.

## Perché esiste

L'ecosistema ha 40+ moduli che devono coesistere in un'unica applicazione. Platform esiste per **centralizzare le decisioni cross-modulo**.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun modulo di feature flag per pannelli | Alta | Aggiungere `FeatureFlag` per abilitare/disabilitare risorse per pannello |
| Manca modulo di configurazione runtime | Alta | Creare `ConfigManager` per modificare configurazioni senza deploy |
| Nessun sistema di onboarding per ogni pannello | Media | Aggiungere `Onboarding` con tour guidati per ogni ruolo |
| Manca modulo di notifiche in-app | Media | Creare `InAppNotifications` come alternativa a email/WhatsApp |
| Nessun modulo di report cross-modulo | Media | Aggiungere `Reporting` con dashboard aggregate |
| Manca modulo di integrazione esterna | Bassa | Creare `Integrations` con connector per servizi terzi |
| Nessun modulo di configurazione ambienti | Bassa | Aggiungere `EnvironmentManager` per gestire config per environment |
| Manca modulo di health check avanzato | Bassa | Estendere `HealthPage` con alerting e metriche |

---

*Documento generato secondo le convenzioni del progetto — modulo `Platform` — data 2026-05-27*