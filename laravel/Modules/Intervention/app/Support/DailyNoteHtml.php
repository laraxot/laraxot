<?php

declare(strict_types=1);

namespace Modules\Intervention\Support;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\HtmlString;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

use function Safe\preg_replace;

final class DailyNoteHtml
{
    public static function sanitize(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowRelativeLinks()
            ->allowElement('span', ['style'])
            ->allowAttribute('style', ['span'])
            ->withAttributeSanitizer(new DailyNoteStyleAttributeSanitizer())
            ->withMaxInputLength(20000);

        return trim((new HtmlSanitizer($config))->sanitize($html));
    }

    public static function toPlainText(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $normalized = preg_replace('/<br\s*\/?>/i', "\n", $html) ?? $html;
        $normalized = is_string($normalized) ? $normalized : $html;
        $normalized = preg_replace('/<\/p>\s*<p>/i', "\n\n", $normalized) ?? $normalized;
        $normalized = is_string($normalized) ? $normalized : $html;
        $normalized = preg_replace('/<\/li>\s*<li>/i', "\n", $normalized) ?? $normalized;
        $normalized = is_string($normalized) ? $normalized : $html;
        $text = html_entity_decode(strip_tags($normalized), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/\r\n?/", "\n", $text) ?? $text;
        $text = is_string($text) ? $text : '';
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;
        $text = is_string($text) ? $text : '';

        return trim($text);
    }

    public static function legacyToHtml(?string $body): string
    {
        $body = trim((string) $body);

        if ($body === '') {
            return '';
        }

        return nl2br(e($body), false);
    }

    /**
     * @param  array<string, string>  $textColors
     */
    public static function render(?string $html, array $textColors = []): HtmlString
    {
        $html = trim((string) $html);

        if ($html === '') {
            return new HtmlString('');
        }

        return new HtmlString(
            RichContentRenderer::make($html)
                ->textColors($textColors)
                ->toHtml()
        );
    }
}
