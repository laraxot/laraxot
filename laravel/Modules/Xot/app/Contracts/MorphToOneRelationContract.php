<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Contract for relations that expose MorphToOne-style create().
 *
 * Optional runtime implementation may come from fidum/laravel-eloquent-morph-to-one.
 */
interface MorphToOneRelationContract
{
    /**
<<<<<<< HEAD
     * @param  array<string, mixed>  $attributes
=======
<<<<<<< .merge_file_tQf45M
     * @param  array<string, mixed>  $attributes
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< .merge_file_2E1dgH.
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< .merge_file_mDfU0I.
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< .merge_file_2E1dgH.
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         >>>>>>> laraxot/dev
     *                                         <<<<<<< .merge_file_uy89WO.
     * @param array<string, mixed> $attributes
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param array<string, mixed> $attributes
     *                                         =======
     * @param array<string, mixed> $attributes
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> .merge_file_bDJ3Gs
     *                                         >>>>>>> .merge_file_JwvZ1t
     *                                         =======
     * @param array<string, mixed> $attributes
     *                                         >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                         <<<<<<< HEAD
     *                                         =======
     *                                         >>>>>>> .merge_file_UnoXtl
     *                                         >>>>>>> laraxot/dev
     *                                         =======
     * @param array<string, mixed> $attributes
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_EwpaaA
>>>>>>> 77b1e2748 (first)
     */
    public function create(array $attributes): Model;
}
