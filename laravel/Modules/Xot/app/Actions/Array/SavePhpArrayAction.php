<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Array;

use Spatie\QueueableAction\QueueableAction;

/**
 * @deprecated Prefer {@see \Modules\Xot\Actions\Arr\SavePhpArrayAction} (namespace Arr).
 *             Wrapper: stessa regola one-key-per-line.
 */
class SavePhpArrayAction
{
    use QueueableAction;

    /**
<<<<<<< .merge_file_QHdD8j
     * @param  array<int|string, mixed>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
>>>>>>> .merge_file_F8tiXx
     */
    public function execute(array $data, string $filename): bool
    {
        return app(\Modules\Xot\Actions\Arr\SavePhpArrayAction::class)->execute($data, $filename);
    }
}
