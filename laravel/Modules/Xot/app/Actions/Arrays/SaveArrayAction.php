<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Arrays;

use Modules\Xot\Actions\Arr\SavePhpArrayAction;
use Spatie\QueueableAction\QueueableAction;

class SaveArrayAction
{
    use QueueableAction;

    /**
<<<<<<< .merge_file_wPAhit
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
     *                                       <<<<<<< .merge_file_l0wgfw
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< .merge_file_tT1x9s.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_OY9ono.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_l0wgfw
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       >>>>>>> laraxot/dev
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       <<<<<<< HEAD
     *                                       >>>>>>> .merge_file_dp2bPs
     *                                       =======
     *                                       >>>>>>> .merge_file_dp2bPs
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> .merge_file_6IHdiT
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> .merge_file_meTjmv
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
>>>>>>> .merge_file_xR8lpt
     */
    public function execute(array $data, string $filename, string $format = 'php'): bool
    {
        return match ($format) {
            'json' => app(SaveJsonArrayAction::class)->execute($data, $filename),
            'php' => app(SavePhpArrayAction::class)->execute($data, $filename),
            default => throw new \InvalidArgumentException("Formato non supportato: {$format}"),
        };
    }
}
