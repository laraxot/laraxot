# 📬 Notify

[![Domain-Notify](https://img.shields.io/badge/Domain-Notifications-E65100.svg)](#)
[![Laravel 12](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com/)
[![Filament 5](https://img.shields.io/badge/Filament-5-ffab00.svg)](https://filamentphp.com/)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4+-777BB4.svg)](https://php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue.svg)](https://www.php-fig.org/psr/psr-12/)
[![Strict Types](https://img.shields.io/badge/PHP-strict__types-1-informational.svg)](#)
[![Laraxot Modules](https://img.shields.io/badge/Architecture-Modular-purple.svg)](#)
[![FixCity Platform](https://img.shields.io/badge/Platform-FixCity-008758.svg)](#)
[![Notify Platform](https://img.shields.io/badge/Platform-Notify-008758.svg)](#)

> **Il cittadino sa cosa succede al suo ticket.** Email, template, canali — orchestrazione notifiche enterprise.

---

<<<<<<< .merge_file_lkaMEP
## Scopo e confini

Notify è il livello di **trasporto** delle comunicazioni verso l'esterno: 46 Action, tutte
`QueueableAction`, organizzate per corriere (11 provider SMS, 8 push FCM, 4 WhatsApp,
4 mail, 3 Telegram) e non per contenuto. Sa come si consegna un messaggio; non sa perché
esista. Sei moduli lo consumano — IndennitaResponsabilita (9 file), Progressioni (6),
Xot (4), Ptv (3), Pdnd (2), User (1).

Il confine da non superare: **la decisione di notificare non è di Notify.** Nasce dove
sta lo stato (`Xot\States\Transitions\XotBaseTransition`, `Ptv\Actions\Scheda\SendMailByRecord`).
Oggi il confine interno più rotto è un altro: 3 modelli su 14 estendono
`Illuminate\...\Model` invece di `BaseModel` e finiscono fuori dalla connection `notify`,
e `docs/` pesa 804.192 righe contro 17.341 di `app/` — 46 a 1, con 83 gruppi di file
`.md` byte-identici.

Scopo esteso, misure e mosse: [docs/scopo.md](docs/scopo.md).

---

## Perché

Un sistema che cambia stato in silenzio genera ticket duplicati, telefonate
all'ufficio e sfiducia — non perché il lavoro non sia stato fatto, ma perché
nessuno lo sapeva. Notify esiste per rendere quel gap strutturalmente
impossibile: ogni evento di dominio che dichiara "questo va comunicato" passa
di qui, non attraverso un `Mail::send()` scritto ad hoc dentro un controller.

## Logica
=======
## Perché esiste
>>>>>>> .merge_file_Bt5am7

Chiude il loop feedback: ogni cambio stato può diventare messaggio tracciabile.

## Superpoteri

- Template mail e layout modulari
- Integrazione eventi dominio ticket
- Filament per configurazione
- BMAD skills e tooling AI nel repo

## Certificazioni

| Certificazione | Stato |
|----------------|-------|
| PHPStan livello 10 | Target progetto |
| `declare(strict_types=1)` | Su nuovo codice PHP |
| Filament 5 + XotBase | Admin enterprise |
| Test PHPUnit / Pest | Suite modulo |
| Documentazione wiki | Cartella `docs/` |

## Vuoi entrare nel team?

Comunicazione **affidabile** = fiducia istituzionale. Qui si implementa.

Stack frontoffice: **Tailwind · Alpine · Lit · DaisyUI · Flowbite · Filament v5** — vedi [STORY-133](../../../docs/stories/STORY-133-frontend-stack-religion-tailwind-alpine-lit.md).

---

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

<<<<<<< .merge_file_lkaMEP
**Modulo** `notify` · **Laraxot / FixCity Platform** · licenza MIT

---

## Scopo del modulo

Perche' esiste, come raggiungere meglio il suo scopo e cosa **non** gli appartiene:
[`docs/purpose.md`](./docs/purpose.md).
=======
**Modulo** `notify` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
**Modulo** `notify` · **Laraxot** · **Notify Platform** · PHPStan 10 · Filament 5
>>>>>>> .merge_file_Bt5am7
