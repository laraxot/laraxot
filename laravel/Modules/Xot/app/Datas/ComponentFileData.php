<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * Undocumented class.
 */
class ComponentFileData extends Data
{
    public string $name = '';

    public string $class = '';

    public ?string $module = null;

    public ?string $path = null;

    public ?string $ns = null;

    /**
<<<<<<< HEAD
     * @param  EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>>  $data
=======
<<<<<<< .merge_file_pkQX7D
     * @param  EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>> $data
     *                                                                                                          =======
     *                                                                                                          <<<<<<< HEAD
     * @param EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>> $data
     *                                                                                                          =======
     *                                                                                                          <<<<<<< HEAD
     *                                                                                                          <<<<<<< HEAD
     * @param EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>> $data
     *                                                                                                          =======
     * @param EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>> $data
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param EloquentCollection<int, object>|Collection<int, object>|array<int, array<array-key, mixed>> $data
     *                                                                                                          >>>>>>> laraxot/dev
     *                                                                                                          >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_CLthOJ
>>>>>>> 77b1e2748 (first)
     * @return DataCollection<int, static>
     */
    public static function collection(EloquentCollection|Collection|array $data): DataCollection
    {
        return self::collect($data, DataCollection::class);
    }
}
