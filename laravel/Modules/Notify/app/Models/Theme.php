<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Model;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme query()
<<<<<<< HEAD
<<<<<<< HEAD
=======
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
>>>>>>> a988596b (first)
=======
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Theme query()
>>>>>>> 98d0a12c (.)
=======
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme query()
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name', 'description', 'colors', 'fonts',
<<<<<<< HEAD
<<<<<<< HEAD
        'version', 'is_active'];
=======
        'version', 'is_active',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'version', 'is_active'];
>>>>>>> a988596b (first)

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'colors' => 'array',
<<<<<<< HEAD
<<<<<<< HEAD
            'fonts' => 'array'];
=======
            'fonts' => 'array',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'fonts' => 'array'];
>>>>>>> a988596b (first)
    }
}
