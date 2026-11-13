<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Xot\Exports\LazyCollectionExport;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportXlsByLazyCollection
{
    use QueueableAction;

    /**
     * Esporta una lazy collection in Excel.
     *
<<<<<<< HEAD
     * @param  LazyCollection<int, mixed>  $collection  La lazy collection da esportare
     * @param  string  $filename  Nome del file Excel
     * @param  array<int, string>  $fields  Campi da includere nell'export
=======
<<<<<<< .merge_file_AXSMq1
     * @param  LazyCollection<int, mixed>  $collection  La lazy collection da esportare
     * @param  string  $filename  Nome del file Excel
     * @param  array<int, string>  $fields  Campi da includere nell'export
=======
     * <<<<<<< HEAD
     *
     * @param LazyCollection<int, mixed> $collection La lazy collection da esportare
     * @param string                     $filename   Nome del file Excel
     * @param array<int, string>         $fields     Campi da includere nell'export
     *                                               =======
     *                                               <<<<<<< HEAD
     *                                               <<<<<<< HEAD
     *                                               =======
     *                                               <<<<<<< HEAD
     *                                               >>>>>>> laraxot/dev
     * @param LazyCollection<int, mixed> $collection La lazy collection da esportare
     * @param string                     $filename   Nome del file Excel
     * @param array<int, string>         $fields     Campi da includere nell'export
     *                                               =======
     * @param LazyCollection<int, mixed> $collection La lazy collection da esportare
     * @param string                     $filename   Nome del file Excel
     * @param array<int, string>         $fields     Campi da includere nell'export
     *                                               >>>>>>> laraxot/dev
     *                                               <<<<<<< HEAD
     *                                               =======
     *                                               =======
     * @param LazyCollection<int, mixed> $collection La lazy collection da esportare
     * @param string                     $filename   Nome del file Excel
     * @param array<int, string>         $fields     Campi da includere nell'export
     *                                               >>>>>>> laraxot/dev
     *                                               >>>>>>> laraxot/dev
     *                                               >>>>>>> laraxot/dev
>>>>>>> .merge_file_uajN9M
>>>>>>> 77b1e2748 (first)
     */
    public function execute(
        LazyCollection $collection,
        string $filename = 'test.xlsx',
        array $fields = [],
    ): BinaryFileResponse {
        // Assicuriamo che $fields sia un array di stringhe
        $stringFields = array_map(strval(...), array_values($fields));

        $export = new LazyCollectionExport($collection, $filename, $stringFields);

        return Excel::download($export, $filename);
    }
}
