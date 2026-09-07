<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Modules\Xot\Models\XotBaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class BaseModel.
 */
abstract class BaseModel extends XotBaseModel implements HasMedia
{
    use InteractsWithMedia;

    public $incrementing = true;

    public $timestamps = true;

    protected $connection = 'notify';

    /** @var list<string> */
    protected $appends = [];

    protected $primaryKey = 'id';

    /** @var string */
    protected $keyType = 'string';

    /** @var list<string> */
    protected $hidden = [];

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'published_at' => 'datetime']);
<<<<<<< HEAD
=======
    /** @return array<string, string> */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'published_at' => 'datetime',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }
}
