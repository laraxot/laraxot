<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Utilities;

use Spatie\QueueableAction\QueueableAction;

class DiffAssocRecursiveAction
{
    use QueueableAction;

    /**
     * Recursively compute difference of arrays with additional index check.
     *
<<<<<<< HEAD
     * @param  array<int|string, mixed>  $array1
     * @param  array<int|string, mixed>  $array2
=======
<<<<<<< .merge_file_y8OzIh
     * @param  array<int|string, mixed>  $array1
     * @param  array<int|string, mixed>  $array2
=======
     * <<<<<<< HEAD
     *
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< .merge_file_QKxFME
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< .merge_file_8bjCZD
     *
     * =======
     * <<<<<<< .merge_file_zjdkGf
     * <<<<<<< HEAD
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * =======
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         >>>>>>> laraxot/dev
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< .merge_file_2uBLQj
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * =======
     * <<<<<<< HEAD
     * <<<<<<< .merge_file_QKxFME
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< .merge_file_8bjCZD
     *                                         >>>>>>> laraxot/dev
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< .merge_file_wMHloO
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         <<<<<<< HEAD
     *                                         >>>>>>> .merge_file_pMmDZ1
     *                                         =======
     *                                         >>>>>>> .merge_file_pMmDZ1
     *                                         >>>>>>> laraxot/dev
     *
     * =======
     * <<<<<<< HEAD
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * =======
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * <<<<<<< HEAD
     * <<<<<<< .merge_file_QKxFME
     * =======
     * =======
     * <<<<<<< .merge_file_QKxFME
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_Sl21n3
     *
     * >>>>>>> .merge_file_wqAv12
     *
     * =======
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * >>>>>>> .merge_file_pMmDZ1
     * =======
     * >>>>>>> .merge_file_pMmDZ1
     *
     * >>>>>>> .merge_file_MjYIQ6
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_25rdgz
     * =======
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *                                         >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_iW1q8z
>>>>>>> 77b1e2748 (first)
     * @return array<int|string, mixed>
     */
    public function execute(array $array1, array $array2): array
    {
        /** @var array<int|string, mixed> $outputDiff */
        $outputDiff = [];
        foreach ($array1 as $key => $value) {
            if (array_key_exists($key, $array2)) {
                if (is_array($value)) {
                    if (! is_array($array2[$key])) {
                        $outputDiff[$key] = $value;
                    } else {
                        /** @var array<int|string, mixed> $nestedArray2 */
                        $nestedArray2 = $array2[$key];
                        $recursiveDiff = app(self::class)->execute($value, $nestedArray2);
                        if (count($recursiveDiff)) {
                            $outputDiff[$key] = $recursiveDiff;
                        }
                    }
                } else {
                    if ($value !== $array2[$key]) {
                        $outputDiff[$key] = $value;
                    }
                }
            } else {
                $outputDiff[$key] = $value;
            }
        }

        return $outputDiff;
    }
}
