<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Arr;

use Spatie\QueueableAction\QueueableAction;

class SaveArrayAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
     * @param  array<int|string, mixed>  $data
=======
<<<<<<< .merge_file_yae9CX
     * @param  array<int|string, mixed>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< .merge_file_Ksm01z.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< .merge_file_k0xji4.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     *                                       <<<<<<< .merge_file_Ksm01z.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       >>>>>>> laraxot/dev
     *                                       <<<<<<< .merge_file_Uwvfnx.
     * @param array<int|string, mixed> $data
     *                                       =======
     *                                       <<<<<<< HEAD
     * @param array<int|string, mixed> $data
     *                                       =======
     * @param array<string, mixed>     $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> .merge_file_A27cnh
     *                                       >>>>>>> .merge_file_NzQM5y
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                       <<<<<<< HEAD
     *                                       =======
     *                                       >>>>>>> .merge_file_lb87ol
     *                                       >>>>>>> laraxot/dev
     *                                       =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
>>>>>>> .merge_file_P9AC3F
>>>>>>> 77b1e2748 (first)
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
