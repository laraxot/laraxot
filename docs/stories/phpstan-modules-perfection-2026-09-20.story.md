---
title: "PHPStan Modules alla perfezione — 2026-09-20"
type: story
status: in-progress
created: 2026-09-20
owner: qwen-code-agent
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---
# Story

Come maintainer voglio che tutti i moduli superino PHPStan con la configurazione canonica, così che i contratti statici riflettano il comportamento reale senza nascondere difetti.

# Acceptance criteria

1. `cd laravel && ./vendor/bin/phpstan analyse Modules --no-progress` termina con exit code 0 e `[OK] No errors`.
2. Nessun file `phpstan*.neon*` viene creato, modificato o usato come configurazione alternativa dalla campagna.
3. Ogni correzione documenta una causa funzionale verificata tramite codice, chiamanti, test o storia Git.
4. Nessun `@phpstan-ignore`, baseline, abbassamento del livello o cast cieco viene introdotto.
5. Tutti i file PHP modificati superano `php -l` e Pint.
6. I test mirati dei moduli modificati passano oppure ogni blocco esterno è riportato con output reale.
7. PHPMD e PHPInsights vengono eseguiti secondo i comandi correnti individuati nel repository, senza dichiarare verdi controlli non eseguiti.
8. Il riepilogo finale distingue modifiche preesistenti e modifiche prodotte dalla campagna in ogni repository interessato.
9. Gli agenti lavorano su scope disgiunti e il coordinamento è registrato in `docs/chat/INDEX.md` o in un handoff dedicato.
10. Non vengono eseguiti commit, push, restore, checkout, reset, revert o rollback.

# Dipendenze

- Analysis: `docs/analysis/phpstan-modules-perfection-2026-09-20.md`
- Planning: `docs/planning/phpstan-modules-perfection-2026-09-20.md`
- Architecture: `docs/architecture/phpstan-modules-perfection-2026-09-20.md`
- Coordination: issue 14 e discussion 23.

## Esecuzione 2026-09-25 — Codex

- Il gate completo ha prima rilevato marker Git PHP committati in `Activity/tests/Unit/Actions/Log/BuildLogViewerStateActionTest.php`: rimossi mantenendo l'import tipizzato `LogFileData` e la closure tipizzata.
- La scansione completa successiva ha prodotto un report transitorio di 600 errori mentre i repository venivano modificati in parallelo; non è stato usato come baseline. Il gate con cache svuotata è attualmente impedito dai marker PHP in conflitti del rebase attivo di `UI`.
- `Geo`: corretti 90 assert `Assert::assertInstanceOf(MapPicker::class, $field)` ridondanti in quattro file già dirty; lint e `git diff --check` verdi. `User`: ripristinata da un agente la method implementation rimossa nel WIP; gate post-fix in attesa del bootstrap. Media/Rating e altri target dirty/lockati restano ai rispettivi owner.
- Coordinamento su scope disgiunti registrato in `docs/chat/INDEX.md`; questa story resta in corso fino a un run completo exit 0 e al controllo Git di tutti i repository.
- Non eseguiti test, commit o push; configurazione PHPStan invariata.
- Nuovo report completo fornito dall'owner: 29 errori in Activity (5), Comment (4), Job (1), Rating (2), UI (16), Xot (1). Wave assegnate per scope: Activity/Rating, Comment/Job, UI/rebase; fix Xot in corso. Da rilanciare il gate completo dopo la quiet window.

## Bootstrap blocker 2026-09-25

Il gate PHPStan completo non è eseguibile: il bootstrap fatale su `unexpected token "<<"` incontra marker in 26 file PHP UI, 14 dei quali non passano `php -l`. UI è in rebase con 31 unmerged paths. Nessun marker strip o resolver automatico è consentito; la story resta `in-progress` fino alla risoluzione semantica dell’owner.

### AI recheck

Il report `phpstan.path` di AI è storico: alle 13:16 UTC AI è pulito, sincronizzato al ref locale, i quattro path esistono e PHPStan AI è verde. Nessun fix AI richiesto.

## Continuazione 2026-09-25 — fix PHPStan verificabili

- Il report globale di 600 errori è stato acquisito mentre i worktree mutavano e non è una baseline attendibile. PHPStan Xot mirato è stato rilanciato dopo i fix e restituisce `[OK] No errors`.
- Xot: riallineato il contratto misto di `CollectionExport` (chiavi numeriche/stringa e intestazioni esplicite), ripristinato il convertitore condiviso `castCell()` usato da `XotBaseExporter`, e reso sicuro il controllo dello status di `pcntl_waitpid()` con un helper tipizzato. Rimossi tre trait non referenziati dopo aver verificato che i metodi sono duplicati nei consumer e che non esistono usi in `laravel/`.
- User/Xot: ripristinata la dichiarazione `UserContract::hasPermissionToOrCreate()` coerente con il trait implementativo e i chiamanti delle policy; PHP lint pulito.
- Media: corretti i call site nei test che invocavano come funzioni globali metodi di `TestCase`; lint e `git diff --check` puliti.
- Blog, Comment, Lang e Quotation/Tenant: fix locali in sette file PHP per contratti lista/view-string/assertion; lint pulito secondo l’agente owner.
- Nessun test, commit o push eseguito da questa continuazione. Il full PHPStan va rilanciato dopo il prossimo quiet window; i moduli ancora in rebase o con WIP concorrente restano esclusi dalle conclusioni finché il report non è stabile.
