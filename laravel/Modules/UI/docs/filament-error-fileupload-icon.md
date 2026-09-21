<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
---
module: theme
topic: filament_error_fileupload_icon
canonical: ../../../Themes/docs/shared-components/filament-error-fileupload-icon_1.md
---
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/filament-error-fileupload-icon_1.md
=======
<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/filament-error-fileupload-icon_1.md
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# Errore: Metodo `icon()` su FileUpload di Filament
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

See canonical documentation: ../../../Themes/docs/shared-components/filament-error-fileupload-icon_1.md
# Errore: Metodo `icon()` su FileUpload di Filament
## Descrizione
Il metodo `icon()` **NON esiste** sul componente `Filament\Forms\Components\FileUpload`. Tentare di utilizzarlo genera un errore fatale.
## Componenti coinvolti
- `FileUpload` **(non supporta)**
- `TextInput`, `Select`, `DatePicker`, `TimePicker` **(supportano)**
## Soluzione
- **Non usare mai** `->icon()` su FileUpload.
- Se serve un'icona, implementare una soluzione custom (ad esempio via slot Blade o CSS personalizzato).
- Per le icone su altri componenti, usare solo dove documentato nell'API ufficiale.
## Best Practice
- Consultare sempre la [documentazione ufficiale Filament](https://filamentphp.com/docs/3.x/forms/fields/file-upload) prima di usare metodi non standard.
- Seguire la tabella di compatibilità dei metodi nei componenti Filament (vedi doc di modulo Patient e Xot).
## Collegamenti
- [Errore e best practice modulo Patient](../../Patient/docs/filament-error-fileupload-icon.md)
- [Tabella metodi supportati](filament-component-methods.md)
## Collegamenti tra versioni di filament-error-fileupload-icon.md
* [filament-error-fileupload-icon.md](../../Patient/docs/filament-error-fileupload-icon.md)
- Consultare sempre la [documentazione ufficiale Filament](https://filamentphp.com/project_docs/3.x/forms/fields/file-upload) prima di usare metodi non standard.
- [Errore e best practice modulo Patient](../../Patient/project_docs/filament-error-fileupload-icon.md)
* [filament-error-fileupload-icon.md](../../Patient/project_docs/filament-error-fileupload-icon.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
See canonical documentation: ../../../Themes/docs/shared-components/filament-error-fileupload-icon_1.md
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
