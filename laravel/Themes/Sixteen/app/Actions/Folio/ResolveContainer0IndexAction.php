<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Folio;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Cms\Models\Page;
use Spatie\QueueableAction\QueueableAction;
use Themes\Sixteen\Infrastructure\Url\FrontofficeContainerAliases;
use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

/**
 * Risolve la shell Folio `container0.index` prima del mount Volt.
 *
 * Alias Design Comuni → 301 al container canonico; area-personale → login/pratiche;
 * shell senza CMS → 404 (mai pagina bianca). La Folio page resta solo trasporto.
 */
final class ResolveContainer0IndexAction
{
    use QueueableAction;

    public function execute(string $container0): ?RedirectResponse
    {
        $canonical = FrontofficeContainerAliases::canonical($container0);
        if ($canonical !== null) {
            return redirect()->to(FrontofficeRoutes::index($canonical), 301);
        }

        if ($container0 === 'area-personale') {
            return redirect()->to(
                Auth::check()
                    ? FrontofficeRoutes::personalAreaPratiche()
                    : route('login'),
            );
        }

        if (Page::findUniqueBySlug($container0.'.index') === null) {
            abort(404);
        }

        return null;
    }
}
