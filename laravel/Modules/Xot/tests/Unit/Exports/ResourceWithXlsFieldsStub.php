<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Exports;

/**
 * Resource fake: espone il contratto `getXlsFields()` con formato misto
 * (chiave intera => percorso, chiave stringa => percorso con label esplicita).
 */
class ResourceWithXlsFieldsStub
{
    /**
<<<<<<< HEAD
     * @param  array<array-key, mixed>  $data
=======
<<<<<<< .merge_file_zMKGby
     * @param  array<array-key, mixed>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< HEAD
     *                                      <<<<<<< .merge_file_zBrJKn.
     * @param array<array-key, mixed> $data
     *
     * =======
     * <<<<<<< .merge_file_JCJjsQ.
     *
     * =======
     * <<<<<<< HEAD
     * <<<<<<< .merge_file_EXpH35.
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< HEAD
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< .merge_file_nCtqKB.
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< .merge_file_zBrJKn.
     * @param array<array-key, mixed> $data
     *
     * =======
     * <<<<<<< .merge_file_JCJjsQ.
     * >>>>>>> laraxot/dev
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< .merge_file_ARVwq6.
     * @param array<array-key, mixed> $data
     *                                      =======
     *                                      <<<<<<< HEAD
     * @param array<array-key, mixed> $data
     *                                      =======
     * @param array<array-key, mixed> $data
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_VDF7Hc
     *
     * >>>>>>> .merge_file_kG4HSa
     * <<<<<<< HEAD
     *
     * >>>>>>> .merge_file_KSvTHm
     * =======
     * >>>>>>> .merge_file_5iYPWw
     *
     * >>>>>>> .merge_file_KSvTHm
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_xXGHYo
     * =======
     * @param array<array-key, mixed> $data
     *                                      >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_m4uedb
>>>>>>> 77b1e2748 (first)
     * @return array<int|string, string>
     */
    public static function getXlsFields(array $data): array
    {
        return [
            'id',
            'matr',
            'ratings_by_id.52.pivot.value' => 'Obiettivo A',
        ];
    }
}
