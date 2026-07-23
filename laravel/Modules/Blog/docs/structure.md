# Modulo Blog

Data: 2025-04-23 19:09:55

## Informazioni generali

- **Namespace principale**: Modules\\Blog
Modules\\Blog\\Database\\Factories
Modules\\Blog\\Database\\Seeders
- **Pacchetto Composer**: laraxot/module_blog_fila5
marco sottana
- **Dipendenze**: pboivin/filament-peek ^2.0 staudenmeir/laravel-adjacency-list ^1.22 spatie/laravel-feed ^4.4 staudenmeir/eloquent-has-many-deep * filament/spatie-laravel-tags-plugin ^3.2 codewithdennis/filament-select-tree ^3.1 thecodingmachine/safe ^2.5 laravel/pint ^1.13 nunomaduro/phpinsights ^2.11 larastan/larastan ^2.7 vimeo/psalm ^5.17 psalm/plugin-laravel ^2.8 enlightn/enlightn ^2.7 driftingly/rector-laravel ^0.26.2 symplify/phpstan-rules * rector/rector * 
- **Totale file PHP**: 202
- **Totale classi/interfacce**: 105

## Struttura delle directory

```

.git
.git/branches
.git/hooks
.git/info
.git/logs
.git/logs/refs
.git/logs/refs/heads
.git/logs/refs/remotes
.git/logs/refs/remotes/aurmich
.git/objects
.git/objects/00
.git/objects/01
.git/objects/02
.git/objects/03
.git/objects/04
.git/objects/05
.git/objects/06
.git/objects/07
.git/objects/09
.git/objects/0a
.git/objects/0c
.git/objects/0e
.git/objects/0f
.git/objects/10
.git/objects/11
.git/objects/12
.git/objects/13
.git/objects/14
.git/objects/15
.git/objects/16
.git/objects/17
.git/objects/18
.git/objects/19
.git/objects/1c
.git/objects/1d
.git/objects/1e
.git/objects/20
.git/objects/21
.git/objects/22
.git/objects/24
.git/objects/25
.git/objects/26
.git/objects/27
.git/objects/29
.git/objects/2a
.git/objects/2b
.git/objects/2c
.git/objects/2d
.git/objects/2e
.git/objects/2f
.git/objects/30
.git/objects/32
.git/objects/33
.git/objects/34
.git/objects/35
.git/objects/36
.git/objects/37
.git/objects/39
.git/objects/3a
.git/objects/3b
.git/objects/3c
.git/objects/3d
.git/objects/3e
.git/objects/3f
.git/objects/40
.git/objects/41
.git/objects/42
.git/objects/43
.git/objects/44
.git/objects/45
.git/objects/46
.git/objects/47
.git/objects/48
.git/objects/49
.git/objects/4a
.git/objects/4b
.git/objects/4c
.git/objects/4d
.git/objects/50
.git/objects/52
.git/objects/53
.git/objects/54
.git/objects/55
.git/objects/56
.git/objects/57
.git/objects/58
.git/objects/5a
.git/objects/5b
.git/objects/5c
.git/objects/5d
.git/objects/5e
.git/objects/5f
.git/objects/61
.git/objects/62
.git/objects/65
.git/objects/66
.git/objects/69
.git/objects/6e
.git/objects/6f
.git/objects/70
.git/objects/71
.git/objects/72
.git/objects/73
.git/objects/75
.git/objects/76
.git/objects/77
.git/objects/79
.git/objects/7a
.git/objects/7b
.git/objects/7c
.git/objects/7d
.git/objects/80
.git/objects/81
.git/objects/82
.git/objects/83
.git/objects/85
.git/objects/86
.git/objects/87
.git/objects/89
.git/objects/8a
.git/objects/8b
.git/objects/8c
.git/objects/8d
.git/objects/8e
.git/objects/8f
.git/objects/91
.git/objects/92
.git/objects/93
.git/objects/95
.git/objects/96
.git/objects/97
.git/objects/98
.git/objects/99
.git/objects/9a
.git/objects/9b
.git/objects/9c
.git/objects/9d
.git/objects/9e
.git/objects/a0
.git/objects/a3
.git/objects/a4
.git/objects/a5
.git/objects/a6
.git/objects/a7
.git/objects/a8
.git/objects/a9
.git/objects/aa
.git/objects/ab
.git/objects/ac
.git/objects/b1
.git/objects/b2
.git/objects/b3
.git/objects/b4
.git/objects/b5
.git/objects/b6
.git/objects/b7
.git/objects/b8
.git/objects/b9
.git/objects/ba
.git/objects/bb
.git/objects/bd
.git/objects/be
.git/objects/c1
.git/objects/c2
.git/objects/c4
.git/objects/c5
.git/objects/c6
.git/objects/c7
.git/objects/c9
.git/objects/cb
.git/objects/cd
.git/objects/ce
.git/objects/cf
.git/objects/d0
.git/objects/d1
.git/objects/d2
.git/objects/d3
.git/objects/d4
.git/objects/d5
.git/objects/d7
.git/objects/d8
.git/objects/d9
.git/objects/da
.git/objects/db
.git/objects/dc
.git/objects/df
.git/objects/e0
.git/objects/e1
.git/objects/e3
.git/objects/e5
.git/objects/e6
.git/objects/e7
.git/objects/e8
.git/objects/e9
.git/objects/eb
.git/objects/ec
.git/objects/ee
.git/objects/ef
.git/objects/f0
.git/objects/f1
.git/objects/f2
.git/objects/f5
.git/objects/fa
.git/objects/fd
.git/objects/fe
.git/objects/ff
.git/objects/info
.git/objects/pack
.git/refs
.git/refs/heads
.git/refs/remotes
.git/refs/remotes/aurmich
.git/refs/tags
.github
.github/ISSUE_TEMPLATE
.github/workflows
.vscode
_docs
app
app/Actions
app/Actions/Article
app/Actions/Banner
app/Actions/Category
app/Actions/ParentChilds
app/Config
app/Console
app/Console/Commands
app/DataObjects
app/Datas
app/Enums
app/Error
app/Events
app/Filament
app/Filament/Actions
app/Filament/Actions/Profile
app/Filament/Blocks
app/Filament/Fields
app/Filament/Pages
app/Filament/Resources
app/Filament/Resources/ArticleResource
app/Filament/Resources/ArticleResource/Pages
app/Filament/Resources/ArticleResource/RelationManagers
app/Filament/Resources/AuthorResource
app/Filament/Resources/AuthorResource/Filters
app/Filament/Resources/BannerResource
app/Filament/Resources/BannerResource/Pages
app/Filament/Resources/CategoryResource
app/Filament/Resources/CategoryResource/Filters
app/Filament/Resources/CategoryResource/Pages
app/Filament/Resources/PostResource
app/Filament/Resources/PostResource/Filters
app/Filament/Resources/ProfileResource
app/Filament/Resources/ProfileResource/Pages
app/Filament/Resources/ProfileResource/RelationManagers
app/Filament/Resources/TextWidgetResource
app/Filament/Resources/TextWidgetResource/Filters
app/Filament/Resources/TextWidgetResource/Pages
app/Filament/Resources/UserResource
app/Filament/Resources/UserResource/Filters
app/Filament/Widgets
app/Http
app/Http/Controllers
app/Http/Livewire
app/Http/Livewire/Article
app/Http/Livewire/Headernav
app/Http/Livewire/Profile
app/Http/Middleware
app/Http/Requests
app/Models
app/Models/Concerns
app/Providers
app/Providers/Filament
app/Traits
app/View
app/View/Components
app/View/Components/Article
app/View/Composers
app/lang
app/lang/ar
app/lang/de
app/lang/en
app/lang/es
app/lang/fr
app/lang/he
app/lang/hy
app/lang/it
app/lang/pt
app/lang/ru
app/lang/tr
app/lang/zh
app/resources
app/resources/assets
app/resources/assets/img
app/resources/assets/js
app/resources/assets/sass
app/resources/images
app/resources/img
app/resources/img/screenshots
app/resources/lang
app/resources/lang/it
app/resources/svg
app/tests
app/tests/Feature
app/tests/Unit
config
database
database/factories
database/migrations
database/seeders
docs
docs/filament
docs/fixes
docs/img
docs/phpstan
docs/providers
resources
resources/assets
resources/assets/img
resources/assets/js
resources/assets/sass
resources/images
resources/lang
resources/lang/ar
resources/lang/de
resources/lang/en
resources/lang/es
resources/lang/fr
resources/lang/he
resources/lang/hy
resources/lang/it
resources/lang/pt
resources/lang/ru
resources/lang/tr
resources/lang/zh
resources/svg
resources/views
resources/views/components
resources/views/components 
routes
tests
tests/Feature
tests/Unit
```

## Namespace e autoload

```json
    "autoload": {
        "psr-4": {
            "Modules\\Blog\\": "app/",
            "Modules\\Blog\\Database\\Factories\\": "database/factories/",
            "Modules\\Blog\\Database\\Seeders\\": "database/seeders/"
        }
    },
    "repositories": [
        {
            "type": "path",
            "url": "../Xot"
        },
        {
            "type": "path",
            "url": "../Tenant"
        },
```

## Dipendenze da altri moduli

-      14 Modules\Xot\Database\Migrations\XotBaseMigration;
-       9 Modules\Xot\Actions\GetViewAction;
-       4 Modules\Xot\View\Components\XotBaseComponent;
-       4 Modules\Xot\Filament\Resources\XotBaseResource;
-       4 Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
-       4 Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;
-       3 Modules\Xot\Traits\Updater;
-       3 Modules\Xot\Datas\XotData;
-       3 Modules\UI\Filament\Blocks\ImageSpatie;
-       2 Modules\Xot\Contracts\UserContract;

## Collegamenti alla documentazione generale

- [Analisi strutturale complessiva](/project_docs/phpstan/modules_structure_analysis.md)
- [Report PHPStan](/project_docs/phpstan/)

