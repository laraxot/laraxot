<?php

declare(strict_types=1);

namespace Modules\Xot\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ViewExport implements FromView
{
    use Exportable;

    public View $view;

    /**
     * Summary of fields.
     *
     * @var array<string>
     */
    public ?array $fields = null;

    public ?string $transKey = null;

    /**
     * Summary of __construct.
     *
<<<<<<< HEAD
     * @param  array<string>  $fields
=======
<<<<<<< .merge_file_gnjksi
     * @param  array<string>  $fields
=======
     * <<<<<<< HEAD
     *
     * @param array<string> $fields
     *                              =======
     *                              <<<<<<< HEAD
     *                              <<<<<<< HEAD
     *                              <<<<<<< .merge_file_SWEpSM
     * @param array<string> $fields
     *                              =======
     *                              =======
     *                              <<<<<<< HEAD
     * @param array<string> $fields
     *                              =======
     *                              <<<<<<< .merge_file_BM1WiU
     * @param array<string> $fields
     *                              =======
     *                              <<<<<<< HEAD
     *                              <<<<<<< .merge_file_SWEpSM
     * @param array<string> $fields
     *                              =======
     *                              >>>>>>> laraxot/dev
     *                              <<<<<<< .merge_file_iIkpeV
     * @param array<string> $fields
     *                              =======
     *                              <<<<<<< HEAD
     * @param array<string> $fields
     *                              =======
     * @param array<string> $fields
     *                              >>>>>>> laraxot/dev
     *                              >>>>>>> .merge_file_NEgiQb
     *                              >>>>>>> .merge_file_imkc7W
     *                              =======
     * @param array<string> $fields
     *                              >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                              <<<<<<< HEAD
     *                              =======
     *                              >>>>>>> .merge_file_ogFscl
     *                              >>>>>>> laraxot/dev
     *                              =======
     * @param array<string> $fields
     *                              >>>>>>> laraxot/dev
     *                              >>>>>>> laraxot/dev
     *                              >>>>>>> laraxot/dev
>>>>>>> .merge_file_zWOkiY
>>>>>>> 77b1e2748 (first)
     */
    public function __construct(View $view, ?string $transKey = null, ?array $fields = null)
    {
        $this->view = $view;
        $this->transKey = $transKey;
        $this->fields = $fields;
    }

    public function view(): View
    {
        return $this->view;
    }
}
