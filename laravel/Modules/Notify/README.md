# Notify: template versionati, canali intercambiabili, un solo punto di invio

<!-- laraxot:badges:start -->
<!-- laraxot:badges:end -->

> **Un `MailTemplate` identificato da uno slug, tre canali in `ChannelEnum` (mail, sms, whatsapp) e una bulk action Filament: scegli il template, spunta i canali, invia a tutti i record selezionati. Il testo vive nel database, il codice non lo conosce.**

## In trenta secondi

Notify è il modulo Laraxot per le comunicazioni in uscita. Tiene i template email in `mail_templates` (modello `MailTemplate`, che estende `Spatie\MailTemplates\Models\MailTemplate` con `HasSlug` e `HasTranslations` su `subject`, `html_template`, `text_template`, `sms_template`), le loro versioni in `mail_template_versions` (`MailTemplateVersion::template()` è un `BelongsTo` con `restoreTemplate()`), i template multicanale in `notification_templates` (`NotificationTemplate` con `compile()`, `shouldSend()` e `preview()`), i temi grafici in `notify_themes` (`NotifyTheme`) e i contatti in `notify_contacts` (`Contact` con `ContactTypeEnum`: phone, mobile, email, pec, whatsapp, fax).

Le Actions in `app/Actions` sono tutte `QueueableAction`: costruiscono il messaggio (`BuildMailMessageAction`), scelgono layout e contenuti stagionali (`Mail/GetMailLayoutAction`, `DetermineSeasonalContentViewPathAction`), normalizzano i numeri (`SMS/NormalizePhoneNumberAction`) e parlano con i provider di SMS, WhatsApp, Telegram e push FCM.

## Perché esiste

Senza un modulo dedicato ogni progetto finisce per avere il testo delle email hardcodato in un `Mailable`, il prefisso telefonico ricalcolato in tre punti diversi e nessun posto dove un operatore possa cambiare un oggetto senza un deploy. Notify sposta il testo in `MailTemplate` (modificabile dalla risorsa Filament "Template Email"), il canale in `ChannelEnum` e il provider in una Action sostituibile: `SendRecordNotificationAction` non sa se lo SMS parte con SmsFactor o Netfun, e non deve saperlo.

## Come funziona

Il percorso più usato è la notifica massiva da una tabella Filament:

```mermaid
flowchart LR
    A[SendRecordsNotificationBulkAction] -->|slug + canali| B[SendRecordsNotificationAction]
    B -->|per record| C[SendRecordNotificationAction]
    C -->|ChannelEnum::getRecipient| D[Notification::route]
    D --> E[RecordNotification]
    E -->|toMail| F[SpatieEmail su MailTemplate]
    E -->|toSms| G[SmsChannel]
```

1. `SendRecordsNotificationBulkAction` (estende `XotBaseBulkAction`) mostra `MailTemplateSelect` e `ChannelCheckboxList`, poi chiama `SendRecordsNotificationAction::execute(Collection $records, string $templateSlug, array $channels)` che ritorna un `SendNotificationBulkResultData` con `successCount`, `errorCount`, `totalProcessed`.
2. Per ogni record `SendRecordNotificationAction::execute(Model $record, string $mailTemplateSlug, array $channels)` ricava il destinatario con `ChannelEnum::getRecipient()` (email validata con `FILTER_VALIDATE_EMAIL`, telefono normalizzato) e chiama `Notification::route($canale, $to)->notify(new RecordNotification($record, $slug))`.
3. `RecordNotification::toMail()` restituisce `SpatieEmail`, un `TemplateMailable` che fa `MailTemplate::firstOrCreate` sullo slug: il primo invio crea il template, i successivi lo riusano. `toSms()` produce uno `SmsData` (`from`, `recipient`, `body`) e rispetta `config('sms.fallback_to')`.
4. Il layout HTML arriva da `GetMailLayoutAction::execute(string $baseName = 'base')`, che cerca in `Themes/<pub_theme>/resources/mail-layouts/` nell'ordine `{base}_{contesto}.html`, `{base}.html`, `christmas-professional.html`, `{contesto}.html`, `base.html`, con fallback `{{{ body }}}`.
5. Il contenuto stagionale lo decide `DetermineSeasonalContentViewPathAction`: Natale dal 1 dicembre al 10 gennaio, Pasqua dal venerdì santo al lunedì dell'angelo (calcolo del computus), estate dal 15 luglio al 31 agosto, Halloween dal 25 ottobre al 1 novembre.

Il secondo percorso, per i template multicanale, è `SendNotificationAction::handle(Model $recipient, string $templateCode, array $data, array $channels, array $options): ?Notification`: cerca il `NotificationTemplate` attivo per `code`, verifica `shouldSend($data)` sulle `conditions`, compila `subject`, `body_html`, `body_text` e smista su `mail`, `database` (salva un record in `notifications`) o `sms`. `SendNotificationJob` lo accoda.

## Il modello dati

| Modello | Tabella | Relazioni e tratti chiave | Base class |
|---|---|---|---|
| `MailTemplate` | `mail_templates` | `HasSlug` da `subject`, `HasTranslations`, `scopeForMailable()`; colonne `mailable`, `slug`, `html_layout_path`, `params`, `counter` | `Spatie\MailTemplates\Models\MailTemplate` |
| `MailTemplateVersion` | `mail_template_versions` | `template(): BelongsTo`, `restoreTemplate()`, unique su (`mail_template_id`, `version`), `SoftDeletes` | `BaseModel` (`XotBaseModel`) |
| `MailTemplateLog` | `mail_template_logs` | `template(): BelongsTo`, `mailable(): MorphTo`, timestamp `sent_at`, `opened_at`, `clicked_at` | `BaseModel` |
| `NotificationTemplate` | `notification_templates` | `type` cast a `NotificationTypeEnum` (email, sms, push), json `channels`, `variables`, `conditions`, `grapesjs_data`; `scopeActive()`, `scopeForChannel()` | `BaseModel` |
| `NotificationTemplateVersion` | `notification_template_versions` | `template(): BelongsTo`, `restoreTemplate()`, trait `Updater` | `BaseModel` |
| `NotificationLog` | `notification_logs` | `notifiable(): MorphTo`, `template(): BelongsTo`, `NotificationLogStatusEnum` (pending, sent, delivered, failed, opened, clicked), `markAsOpened()`, `markAsClicked()` | `BaseModel` |
| `Notification` | `notifications` | `channels` e `data` json, `read_at`, `sent_at`, `tenant_id` | `Modules\Xot\Models\BaseModel` |
| `NotificationType` | `notification_types` | `channels` e `settings` json, `HasFactory` | `Illuminate\Database\Eloquent\Model` |
| `NotificationChannel` | `notification_channels` | `driver`, `config` json, `is_enabled`, `priority` | `BaseModel` |
| `NotifyTheme` | `notify_themes` | `linkable(): MorphTo` su `post`, accessor `logo` da Media Library, `view_params` json | `BaseModel` |
| `NotifyThemeable` | `notify_themeables` | pivot morfico `model` + `notify_theme_id` | `BaseMorphPivot` |
| `Contact` | `notify_contacts` | `contact_type`, `value`, `verified_at`, contatori `sms_count`, `mail_count`, `sms_status_code` | `BaseModel` |

`BaseModel` del modulo estende `XotBaseModel` e implementa `HasMedia`. Le migrazioni estendono `XotBaseMigration` e usano `tableCreate()` per lo schema iniziale e `tableUpdate()` per le colonne aggiunte nel tempo.

## Superpoteri

| Cosa | Dove | Firma o contenuto |
|---|---|---|
| Invio massivo da tabella | `app/Filament/Actions/SendRecordsNotificationBulkAction.php` | `execute(Collection $records, string $templateSlug, array $channels): SendNotificationBulkResultData` |
| Invio singolo per record | `app/Actions/SendRecordNotificationAction.php` | `execute(Model $record, string $mailTemplateSlug, array $channels): void` |
| Invio da template multicanale | `app/Actions/SendNotificationAction.php` | `handle(Model $recipient, string $templateCode, array $data = [], array $channels = [], array $options = []): ?Notification` |
| Messaggio da tema | `app/Actions/BuildMailMessageAction.php` | `execute(string $name, Model $model, array $view_params = [], ?array $attachments = null): MailMessage` |
| Tema per lingua e tipo | `app/Actions/NotifyTheme/Get.php` | `execute(string $name, string $type, array $view_params): NotifyThemeData`, `firstOrCreate` su `lang`, `type`, `post_type`, `post_id` |
| Layout stagionale | `app/Actions/Mail/GetMailLayoutAction.php` | `execute(string $baseName = 'base'): string` |
| Vista stagionale | `app/Actions/DetermineSeasonalContentViewPathAction.php` | `execute(string $defaultViewName = 'base-content'): string` |
| Numeri italiani | `app/Actions/SMS/NormalizePhoneNumberAction.php` | `execute(string $phoneNumber): string`, toglie parentesi e zeri iniziali, antepone `+39` |
| Numeri E.164 | `app/Actions/NormalizePhoneNumberAction.php` | `execute(?string $phoneNumber): string`, converte `00` in `+` |
| SMS | `app/Actions/SMS/`, `app/Actions/EsendexSendAction.php`, `app/Actions/NetfunSendAction.php` | `execute(SmsData $smsData): array`; `SmsActionFactory::create(?string $driver): SmsActionContract` |
| WhatsApp | `app/Actions/WhatsApp/` | `Send360dialogWhatsAppAction`, `SendFacebookWhatsAppAction`, `SendTwilioWhatsAppAction`, `SendVonageWhatsAppAction`, tutte `execute(WhatsAppData): array` |
| Telegram | `app/Actions/Telegram/` | `SendOfficialTelegramAction`, `SendBotmanTelegramAction`, `SendNutgramTelegramAction`, `execute(TelegramData): array` |
| Push FCM | `app/Actions/Push/` | `SendPushToDeviceAction::execute(string $token, PushNotificationData $notification, array $data = []): array`, più topic, piattaforma, targeting e template |
| Allegato PDF da tema | `app/Actions/NotifyTheme/Attachment/Pdf.php` | `execute(string $post_type, array $view_params): AttachmentData` |

Risorse Filament in `app/Filament/Resources`: `ContactResource`, `NotificationResource`, `NotificationTemplateResource` (con pagina `PreviewNotificationTemplate`) e `NotifyThemeResource` (con `LinkableRelationManager`) estendono `XotBaseResource`; `MailTemplateResource` estende `LangBaseResource` e aggiunge `PreviewMailTemplate`. Le pagine Create, Edit, List e View estendono `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseListRecords`, `XotBaseViewRecord`.

Il cluster `app/Filament/Clusters/Test.php` (`XotBaseCluster`) raccoglie le pagine di prova `SendEmailPage`, `SendSpatieEmailPage`, `SendAwsEmailPage`, `TestSmtpPage`, `SendSmsPage`, `SendNetfunSmsPage`, `SendWhatsAppPage`, `SendTelegramPage`, `SendPushNotificationPage`, `SendFirebasePushNotificationPage`, `SlackNotificationPage`: ognuna estende `XotBasePage` e chiama la Action del canale con dati inseriti a mano.

`AdminPanelProvider` estende `XotBasePanelProvider` e, se `XotData::disable_database_notifications` è falso, registra il trigger `notify::livewire.database-notifications-trigger` con polling a 60 secondi nel menu utente.

Comandi artisan in `app/Console/Commands`:

| Comando | Cosa fa |
|---|---|
| `php artisan notify:send-mail` | chiede destinatario, oggetto, mittente e corpo HTML e costruisce un `EmailData` |
| `php artisan notify:cleanup-logs --days=30 --batch=1000` | cancella i `NotificationLog` più vecchi di N giorni a blocchi con `chunkById`, conservando i `FAILED` |
| `php artisan notify:analyze-translations` | segnala incoerenze tra i file di `lang/` del modulo |
| `php artisan telegram:set-webhook` | scheletro per registrare il webhook Telegram (corpo ancora commentato) |

## Esempio reale

Da `tests/Unit/Actions/SendRecordsNotificationActionTest.php`: due record, un template, due canali, quattro invii contati.

```php
$result = app(SendRecordsNotificationAction::class)->execute(
    records: $records,
    templateSlug: 'welcome-template',
    channels: ['mail', 'sms'],
);

Assert::assertSame(4, $result->successCount);
Assert::assertSame(0, $result->errorCount);
Assert::assertSame(4, $result->totalProcessed);
```

## Numeri veri

<!-- laraxot:metrics:start -->
<!-- laraxot:metrics:end -->

La misura di copertura documentata in [docs/coverage.md](./docs/coverage.md) è del 6,3% con 300 test saltati per database di test irraggiungibile: è la copertura della sola parte Unit senza DB, non quella del modulo.

## La visione

Chi scrive il testo non tocca il codice: modifica un `MailTemplate` o un `NotificationTemplate` dal pannello, lo vede in anteprima con `PreviewMailTemplate` e, se sbaglia, torna alla versione precedente con `MailTemplateVersion::restoreTemplate()`. Chi scrive il codice non tocca il testo: passa uno slug e un elenco di `ChannelEnum`, e cambia provider sostituendo una Action senza toccare chi la chiama.

## Lo scopo

- Tenere i template email in `mail_templates` con slug, traduzioni e versioni, e i template multicanale in `notification_templates` con condizioni di invio.
- Spedire lo stesso messaggio su mail, sms e whatsapp attraverso `ChannelEnum` e i canali `SmsChannel`, `WhatsAppChannel`, `TelegramChannel`, `NetfunChannel`.
- Isolare ogni provider esterno (SmsFactor, Netfun, Esendex, Twilio, Vonage, 360dialog, Facebook, Telegram, FCM) dietro una Action con `execute()` tipizzato su un DTO Spatie Data.
- Dare ai temi grafici (`NotifyTheme`, layout `ark`, `minty`, `sunny`, `widgets`, `empty` in `resources/views/emails/templates`) un posto nel database, non nel codice.

## Politica

- Nessuna classe Filament estesa direttamente: risorse su `XotBaseResource` o `LangBaseResource`, pagine su `XotBasePage`, cluster su `XotBaseCluster`, bulk action su `XotBaseBulkAction`.
- Nessun `env()` nei file di `config/` né nei provider: `MergesNotifyConfigFromEnv` legge `config('notify-env')` del progetto host e lo riversa in `notify.notify.*`, `notify.config.*`, `notify.sms.*`, `notify.whatsapp.*`, `notify.telegram.*`.
- In ambienti non di produzione `NotifyServiceProvider::boot()` legge `mail.fallback_to` tramite `ResolveTenantConfigValueAction` e, se presente, forza `Mail::alwaysTo()`: nessuna email finisce a un cliente vero per sbaglio.
- I driver SMS attivi sono quelli in `SmsDriverEnum` (`smsfactor`) e in `SmsActionFactory`; le altre Actions in `app/Actions/SMS/` restano raggiungibili solo per chiamata diretta, come registrato in [docs/wiki/decisions/sms-actions-consolidation-2026-06-30.md](./docs/wiki/decisions/sms-actions-consolidation-2026-06-30.md).
- Nessun controller HTTP senza route: lo verifica `tests/Unit/Architecture/NoOrphanHttpControllersTest.php`.
- Le label dei form vengono da `lang/it/*.php` (per esempio `mail_template.php`, `notify_theme.php`, `contact.php`), mai da `->label()` nel codice.

## Religione

- `BaseModel extends XotBaseModel implements HasMedia`; pivot su `BasePivot` e `BaseMorphPivot` con trait `Updater` di Xot.
- Migrazioni su `XotBaseMigration` con `tableCreate()` e `tableUpdate()`.
- Ogni Action usa `Spatie\QueueableAction\QueueableAction` ed espone `execute()` (o `handle()` in `SendNotificationAction`).
- Input e output tipizzati con `Spatie\LaravelData\Data`: `EmailData`, `SmsData`, `WhatsAppData`, `TelegramData`, `PushNotificationData`, `NotifyThemeData`, `SendNotificationBulkResultData`.
- Email su template database con `Spatie\MailTemplates\TemplateMailable` (`SpatieEmail`) e traduzioni con `Spatie\Translatable\HasTranslations`.
- Provider su `XotBaseServiceProvider`, `XotBaseRouteServiceProvider`, `XotBasePanelProvider`.

## Filosofia

Avvisare bene è rispettare il tempo di chi legge. [docs/philosophy.md](./docs/philosophy.md) la chiama "comunicazione responsabile": ogni messaggio ha un intento dichiarato (lo slug), un destinatario che può riceverlo su quel canale (`ChannelEnum::getRecipient()` ritorna `null` e il canale viene saltato) e un contenuto che si può leggere in anteprima prima di partire.

## Zen

Un solo slug, molti canali: il messaggio non sa da dove parte, e non deve saperlo.

## Configurazione

| File | Chiavi reali |
|---|---|
| `config/config.php` | `name`, `icon` (`heroicon-o-bell`), `navigation.sort` (70), `default_layout`, `layouts.default`, `templates.welcome`, `logo_url`, `unsubscribe_url`, `social_links` |
| `config/notify.php` | `company.*`, `email.default_from_address`, `email.default_from_name`, `paths.*`, `template_variables.*`, `webhooks.notification_delivered`, `webhooks.notification_bounced`, `webhooks.notification_clicked` |
| `config/sms.php` | `default` (`smsfactor`), `drivers.smsfactor.token`, `drivers.smsfactor.base_url`, `retry.attempts`, `rate_limit.max_attempts`, `circuit_breaker.threshold`, `validation.pattern` (E.164) |
| `config/whatsapp.php` | `default` (`twilio`), `drivers.twilio`, `drivers.vonage`, `drivers.facebook`, `drivers.360dialog`, `from`, `retry`, `rate_limit` |
| `config/telegram.php` | `default` (`official`), `drivers.official`, `drivers.botman`, `drivers.nutgram`, `parse_mode` (`HTML`), `rate_limit.max_attempts` (30) |

Chiavi lette a runtime fuori da questi file: `notify-env` (sorgente unica delle credenziali nel progetto host), `mail.fallback_to` (via Tenant), `sms.fallback_to`, `notify.cleanup.older_than_days`, `notify.cleanup.batch_size`.

## Quickstart

```bash
php artisan module:enable Notify
./vendor/bin/phpstan analyse Modules/Notify --memory-limit=-1
./vendor/bin/pest Modules/Notify/tests
php artisan notify:send-mail
```

Poi, dal pannello: crea un "Template Email" (gruppo Notifiche), aprilo in anteprima con `PreviewMailTemplate`, e da qualsiasi tabella che monta `SendRecordsNotificationBulkAction` seleziona i record, scegli lo slug e i canali.

## Documentazione

<!-- laraxot:docs:start -->
<!-- laraxot:docs:end -->

Punti di ingresso: [docs/00-index.md](./docs/00-index.md), [docs/email-templates.md](./docs/email-templates.md) per i template Spatie e stagionali, [docs/provider-actions-architecture.md](./docs/provider-actions-architecture.md) per aggiungere un driver, [docs/send-notification-bulk-action.md](./docs/send-notification-bulk-action.md) per l'invio massivo, [docs/stories](./docs/stories) per le story BMAD del modulo.

## Ecosistema

Dipendenze da `composer.json`: `spatie/laravel-database-mail-templates`, `laravel-notification-channels/fcm`, `laravel-notification-channels/telegram`, `irazasyed/telegram-bot-sdk`, `kreait/laravel-firebase`, `aws/aws-sdk-php`, `symfony/postmark-mailer`, `symfony/http-client`. Repository path locali: `Xot`, `Tenant`, `UI`.

Moduli usati nel codice: `Xot` (classi base, `XotData`, `SafeEloquentCastAction`), `Tenant` (`ResolveTenantConfigValueAction`), `Media` (`Modules\Media\Models\Media` nei modelli con `HasMedia`), `Lang` (`LangBaseResource`).

Moduli che consumano Notify: `Xot` (`SendMailByRecordAction` usa `EmailData` e `SmtpData`; `XotBaseTransition` invia `RecordNotification` con `RecordNotificationData`) e `User` (`UserServiceProvider` registra `SpatieEmail`).

---

Modulo `Notify` della famiglia **Laraxot**. Badge e numeri si rigenerano con `bash bashscripts/tools/readme/module-readme-badges.sh Notify`; il testo si cura a mano.
