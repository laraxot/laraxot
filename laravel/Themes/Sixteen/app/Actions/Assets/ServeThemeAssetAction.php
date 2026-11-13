<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Assets;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ServeThemeAssetAction
{
    public function execute(string $path): BinaryFileResponse
    {
        $relativePath = ltrim(str_replace('\\', '/', $path), '/');

        if ($relativePath === '' || str_contains($relativePath, '..')) {
            throw new NotFoundHttpException;
        }

        $assetPath = base_path('Themes/Sixteen/public/'.$relativePath);

        // The Design-Comuni logo is source-controlled with the theme but is
        // intentionally not duplicated into every generated Vite output.
        if (! is_file($assetPath) && $relativePath === 'design-comuni/assets/images/logo-comune.svg') {
            $assetPath = base_path('Themes/Sixteen/Main_files/five/assets/images/logo-comune.svg');
        }

        if (! is_file($assetPath)) {
            throw new NotFoundHttpException;
        }

        return response()->file($assetPath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
