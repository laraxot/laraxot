# XotBasePanelProvider — scoperta dei componenti del modulo

`XotBasePanelProvider::panel()` scopre automaticamente resource, pagine, widget e
cluster del modulo proprietario:

```php
->discoverResources(base_path('Modules/'.$this->module.'/app/Filament/Resources'), ...)
->discoverPages(...)
->discoverWidgets(...)
->discoverClusters(...)
```

E' il comportamento giusto per un pannello amministrativo: chi ci entra ha accesso al
modulo, e aggiungere una resource nella cartella deve bastare a vederla.

## Quando spegnerla: `$discoverModuleComponents = false`

Per i pannelli aperti a **utenti esterni** la scoperta automatica e' pericolosa: il
pannello eredita tutto cio' che sta nella cartella del modulo che lo ospita, comprese
schermate che quell'utente non deve nemmeno sapere che esistono.

Caso reale (2026-08-31): il pannello `/customer` vive nel modulo Platform, che contiene
anche `AuditLogResource`. Con la scoperta accesa il cliente arrivava su
`/customer/audit-logs` e riceveva **200**.

Il gate della resource non salvava la situazione, e non poteva:
`AccountFeatures::currentPanelAllowsFeature()` nel ramo `customer` traduce
`admin.audit_log` in `customer.admin.audit_log` con default **true**, perche' e' lo
stesso default che fa vedere al cliente le sue commesse e le sue fatture.

```php
class CustomerPanelProvider extends XotBasePanelProvider
{
    protected bool $discoverModuleComponents = false;

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->resources([WorkOrderResource::class, InvoiceResource::class])
            ->pages([Dashboard::class, CustomerInterventionCalendar::class]);
    }
}
```

## Regola

- Pannello amministrativo di modulo → scoperta **accesa** (default).
- Pannello per utenti esterni (cliente, fornitore) → scoperta **spenta**, schermate
  montate a mano. Il gate resta la seconda linea di difesa, non l'unica.

Spegnendo la scoperta serve montare esplicitamente anche una pagina sulla radice:
senza, Filament ricade sul redirect controller e l'utente finisce sulla prima resource
che capita.
