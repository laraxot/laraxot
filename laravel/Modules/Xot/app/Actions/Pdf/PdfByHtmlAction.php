<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Pdf;

use Modules\Xot\Datas\PdfData;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfByHtmlAction
{
    use QueueableAction;

    public PdfEngineEnum $engine;

    /**
     * Genera un PDF dall'HTML fornito.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_fFZ5Oq
>>>>>>> 77b1e2748 (first)
     * @param  string  $html  Contenuto HTML da convertire
     * @param  string  $filename  Nome del file PDF
     * @param  string  $disk  Disco di storage
     * @param  string  $out  Tipo di output (download, path, etc.)
     * @param  string  $orientation  Orientamento (P=Portrait, L=Landscape)
     * @param  PdfEngineEnum  $engine  Engine da utilizzare
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_7F9KEC
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   >>>>>>> laraxot/dev
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   =======
     *                                   <<<<<<< .merge_file_fiqDWW
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_7F9KEC
     *                                   >>>>>>> .merge_file_EziQIl
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   <<<<<<< .merge_file_fiqDWW
     *                                   =======
     *                                   =======
     *                                   >>>>>>> laraxot/dev
     *                                   <<<<<<< .merge_file_oVI9nZ
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   =======
     *                                   <<<<<<< HEAD
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   =======
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> .merge_file_LXtK0g
     *                                   >>>>>>> .merge_file_8HFKTD
     *                                   =======
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                   <<<<<<< HEAD
     *                                   =======
     *                                   >>>>>>> .merge_file_EziQIl
     *                                   >>>>>>> laraxot/dev
     *                                   =======
     * @param string        $html        Contenuto HTML da convertire
     * @param string        $filename    Nome del file PDF
     * @param string        $disk        Disco di storage
     * @param string        $out         Tipo di output (download, path, etc.)
     * @param string        $orientation Orientamento (P=Portrait, L=Landscape)
     * @param PdfEngineEnum $engine      Engine da utilizzare
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_LH6aXd
>>>>>>> 77b1e2748 (first)
     */
    public function execute(
        string $html,
        string $filename = 'my_doc.pdf',
        string $disk = 'cache',
        string $out = 'download',
        string $orientation = 'P',
        PdfEngineEnum $engine = PdfEngineEnum::SPIPU,
    ): string|BinaryFileResponse {
        $data = PdfData::from([
            'html' => $html,
            'filename' => $filename,
            'disk' => $disk,
            'out' => $out,
            'orientation' => $orientation,
            'engine' => $engine,
        ]);

        // Genera il PDF utilizzando PdfData
        $data->fromHtml($html);

        // Restituisce il risultato in base al tipo di output richiesto
        return match ($out) {
            'download' => $data->download(),
            'path' => $data->getPath(),
            default => $data->getPath(),
        };
    }
}
