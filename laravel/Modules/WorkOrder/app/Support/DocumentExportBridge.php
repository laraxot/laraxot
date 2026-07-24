<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Support;

use RuntimeException;

/**
 * Bridge verso barryvdh/laravel-dompdf senza hard-dep per PHPStan.
 * In prod: `composer require barryvdh/laravel-dompdf`.
 * DOCX: `composer require phpoffice/phpword` (generateDocx attualmente throw).
 */
final class DocumentExportBridge
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function pdfFromView(
        string $view,
        array $data,
        string $paper = 'a4',
        string $orientation = 'portrait',
    ): string {
        $class = 'Barryvdh\\DomPDF\\Facade\\Pdf';
        if (! class_exists($class)) {
            throw new RuntimeException('Missing dependency: barryvdh/laravel-dompdf');
        }

        $loaded = $class::loadView($view, $data);
        if (! is_object($loaded)) {
            throw new RuntimeException('DomPDF loadView() did not return object');
        }
        if (method_exists($loaded, 'setPaper')) {
            $loaded->setPaper($paper, $orientation);
        }
        if (! method_exists($loaded, 'output')) {
            throw new RuntimeException('DomPDF facade missing output()');
        }

        $content = $loaded->output();
        if (! is_string($content)) {
            throw new RuntimeException('DomPDF output() did not return string');
        }

        return $content;
    }
}
