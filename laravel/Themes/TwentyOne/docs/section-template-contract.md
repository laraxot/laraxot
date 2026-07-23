# TwentyOne Section Template Contract

## Scopo

Il componente CMS `x-section` non renderizza direttamente `components.sections.header` o `components.sections.footer`.
Costruisce invece sempre una view nel formato:

```text
pub_theme::components.sections.{slug}.v1
```

## Implicazione per TwentyOne

Per gli slug usati dai layout pubblici del tema devono esistere almeno:

- `resources/views/components/sections/header/v1.blade.php`
- `resources/views/components/sections/footer/v1.blade.php`

Anche se esistono gia' file flat come:

- `resources/views/components/sections/header.blade.php`
- `resources/views/components/sections/footer.blade.php`

senza i wrapper `v1` la homepage pubblica puo' andare in `500` con errore:

```text
View [components.sections.header.v1] not found.
```

## Regola forward-only

Non cambiare il contratto del componente CMS per inseguire un singolo tema.
Se il tema e' incompleto rispetto al contratto, aggiungere i wrapper mancanti nel tema.

## Riferimento studiato

Il pattern e' coerente con il tema `Meetup` del progetto `base_laravelpizza`, dove esistono gia':

- `Themes/Meetup/resources/views/components/sections/header/v1.blade.php`
- `Themes/Meetup/resources/views/components/sections/footer/v1.blade.php`

## Collegamenti

- [Theme docs index](./README.md)
- [Theme asset pipeline governance](../../../../docs/project/THEME_ASSET_PIPELINE_GOVERNANCE.md)
