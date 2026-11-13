<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

interface PdfBuilderContract
{
    public function format(string $format): self;

    public function name(string $filename): self;

    public function download(): self;

    /**
<<<<<<< HEAD
     * @param  \Closure(object): void  $callback
=======
<<<<<<< .merge_file_CwslsU
     * @param  \Closure(object): void  $callback
=======
     * <<<<<<< HEAD.
     *
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< .merge_file_cbfm32.
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         <<<<<<< .merge_file_3IrfSn.
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         <<<<<<< HEAD
     *                                         <<<<<<< .merge_file_cbfm32.
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         >>>>>>> laraxot/dev
     *                                         <<<<<<< .merge_file_BKEGs0.
     * @param \Closure(object): void $callback
     *                                         =======
     *                                         <<<<<<< HEAD
     * @param \Closure(object): void $callback
     *                                         =======
     * @param \Closure(object): void $callback
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> .merge_file_kMmTVQ
     *                                         >>>>>>> .merge_file_ZG1xGl
     *                                         =======
     * @param \Closure(object): void $callback
     *                                         >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                         <<<<<<< HEAD
     *                                         =======
     *                                         >>>>>>> .merge_file_h9wiY0
     *                                         >>>>>>> laraxot/dev
     *                                         =======
     * @param \Closure(object): void $callback
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
     *                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_a3XlYd
>>>>>>> 77b1e2748 (first)
     */
    public function withBrowsershot(\Closure $callback): self;

    public function base64(): string;
}
