<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Array;

use Spatie\QueueableAction\QueueableAction;

/**
 * ---.
 */
class DiffAssocRecursiveAction
{
    use QueueableAction;

    /**
<<<<<<< .merge_file_mmemDJ
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
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int|string, mixed> $data
     *                                       >>>>>>> laraxot/dev
     *                                       >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_K7Epgm
     * @return array<int|string, array<int|string, mixed>>
     */
    public static function fixType(array $data): array
    {
        $collection = collect($data)->map(static function (mixed $item) {
            if (! is_array($item)) {
                throw new \Exception('['.__LINE__.']['.self::class.']');
            }

            return collect($item)->map(static function (mixed $item0) {
                if (is_numeric($item0)) {
                    $item0 *= 1;
                }

                return $item0;
            })->all();
        });

        return $collection->all();
    }

    /**
<<<<<<< .merge_file_mmemDJ
     * @param  array<int|string, mixed>  $arr_1
     * @param  array<int|string, mixed>  $arr_2
=======
     * <<<<<<< HEAD.
     *
     * @param array<int|string, mixed> $arr_1
     * @param array<int|string, mixed> $arr_2
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param array<int|string, mixed> $arr_1
     * @param array<int|string, mixed> $arr_2
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param array<int|string, mixed> $arr_1
     * @param array<int|string, mixed> $arr_2
     *                                        =======
     * @param array<int|string, mixed> $arr_1
     * @param array<int|string, mixed> $arr_2
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int|string, mixed> $arr_1
     * @param array<int|string, mixed> $arr_2
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_K7Epgm
     * @return array<int|string, array<int|string, mixed>>
     */
    public function execute(array $arr_1, array $arr_2): array
    {
        $coll_1 = collect(self::fixType($arr_1));
        $arr_2 = self::fixType($arr_2);

        $ris = $coll_1->filter(static function (array $value, int|string $key) use ($arr_2) {
            try {
                return ! \in_array($value, $arr_2, false);
            } catch (\Exception $exception) {
                throw $exception;
            }
        });

        return $ris->all();
    }
}
