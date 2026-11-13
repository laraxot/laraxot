<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Arrays;

use Spatie\QueueableAction\QueueableAction;

/**
 * @deprecated Prefer {@see \Modules\Xot\Actions\Arr\SavePhpArrayAction} (namespace Arr).
 *             Wrapper: stessa regola one-key-per-line.
 */
class SavePhpArrayAction
{
    use QueueableAction;

    /**
<<<<<<< .merge_file_toKQGz
     * @param  array<int|string, mixed>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_gemX0T
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< .merge_file_GDeLjX.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_EvqBne.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_gemX0T
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       >>>>>>> laraxot/dev
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       <<<<<<< HEAD
     *                                       >>>>>>> .merge_file_z3uypR
     *                                       =======
     *                                       >>>>>>> .merge_file_z3uypR
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> .merge_file_n4lYsv
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> .merge_file_xbdtjz
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
>>>>>>> .merge_file_qZEEIZ
     */
    public function execute(array $data, string $filename): bool
    {
        return app(\Modules\Xot\Actions\Arr\SavePhpArrayAction::class)->execute($data, $filename);
    }
}
