# 25f — Correggere test con static call getFormSchema() su Resource

**Modulo:** multi (Activity, Cms, Job, Lang, Media, Notify, Tenant, User)
**File:** `tests/*/*/*Test.php` che chiamano `Resource::getFormSchema()`
**Fix:** sostituire con `app(ResourceSchema::class)->getFormSchema()` o rimuovere test non più valido.
**Regola BMAD:** `getFormSchema()` non è su `XotBaseResource` ma su `XotBaseResourceForm`.

**File noti:**
- Activity: `tests/Feature/FilamentTest.php`, `tests/Unit/Filament/ResourceExtensionTest.php`
- Cms: `tests/Unit/Filament/Resources/*.php`
- Job, Lang, Media, Notify, Tenant, User
