<?php

declare(strict_types=1);

namespace Themes\Sixteen\Infrastructure\Assets;

use function Safe\preg_match;

/**
 * URL degli asset Design Comuni (sprite delle icone, immagini, loghi) nelle viste del tema.
 *
 * Il riferimento (italia.github.io/design-comuni-pagine-statiche) li cita con percorsi relativi (`../assets/...`): per avere gli stessi valori
 * di `href`/`src` nell'HTML delle pagine con chrome Design Comuni (`pages/tests/*` e pagine reali con JSON `layout` design-comuni*), la pagina
 * Folio condivide `dcRelativeAssets` e qui il prefisso diventa relativo al percorso corrente: `/{lingua}/x/y` -> `../assets`, `/{lingua}/x` -> `./assets`.
 * La rotta `/{lingua}/assets/{path}` (routes/web.php) serve gli stessi file di `themes/Sixteen/design-comuni/assets`.
 * Senza il flag (sito reale, aggiornamenti Livewire) l'URL resta assoluto come prima. Vedi DCP-94 e DCP-99.
 */
final class DesignComuniAssets
{
    private const ABSOLUTE_PREFIX = 'themes/Sixteen/design-comuni/assets';

    public static function base(): string
    {
        return self::relativeBase() ?? asset(self::ABSOLUTE_PREFIX);
    }

    /**
     * Come `asset($path)`, ma un percorso dentro `themes/Sixteen/design-comuni/assets/` segue la regola di {@see base()}.
     */
    public static function url(string $path): string
    {
        $prefix = self::ABSOLUTE_PREFIX.'/';
        $relative = self::relativeBase();

        return $relative !== null && str_starts_with($path, $prefix)
            ? $relative.'/'.substr($path, strlen($prefix))
            : asset($path);
    }

    private static function relativeBase(): ?string
    {
        if (! app('view')->shared('dcRelativeAssets', false)) {
            return null;
        }

        // directory del documento come la vede il browser: `/it/tests/x` e `/it/tests/` hanno la stessa (`/it/tests/`), `/it/tests` ha `/it/`
        $path = '/'.ltrim(request()->getPathInfo(), '/');
        $directory = array_values(array_filter(explode('/', substr($path, 0, (int) strrpos($path, '/') + 1)), static fn (string $part): bool => $part !== ''));
        if ($directory === [] || preg_match('/^[a-z]{2}$/', $directory[0]) !== 1) {
            return null;
        }

        return (count($directory) === 1 ? '.' : rtrim(str_repeat('../', count($directory) - 1), '/')).'/assets';
    }
}
