<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Fixtures;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

final class SendRecordsNotificationBulkActionSpy
{
    /** @var array{count: int, slug: string, channels: array<int, string>}|null */
    public ?array $received = null;

    /**
     * @param  EloquentCollection<int, Model>  $records
     * @param  array<int, string>  $channels
     */
    public function execute(EloquentCollection $records, string $templateSlug, array $channels): void
    {
        $this->received = [
            'count' => $records->count(),
            'slug' => $templateSlug,
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => $channels];
=======
            'channels' => $channels,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => $channels];
>>>>>>> a988596b (first)
    }
}
