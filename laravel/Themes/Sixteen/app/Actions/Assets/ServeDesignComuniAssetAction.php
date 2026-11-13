<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Assets;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * `/{lingua}/assets/{path}`: gli asset Design Comuni con il percorso relativo del riferimento (`../assets/...`, vedi DesignComuniAssets).
 * Delega a {@see ServeThemeAssetAction}, che confina il percorso in `Themes/Sixteen/public` e rifiuta `..`.
 */
final class ServeDesignComuniAssetAction
{
    public function __invoke(string $lang, string $path): BinaryFileResponse
    {
        return (new ServeThemeAssetAction)->execute('design-comuni/assets/'.$path);
    }
}
