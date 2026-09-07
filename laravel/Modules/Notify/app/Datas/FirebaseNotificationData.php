<?php

declare(strict_types=1);

namespace Modules\Notify\Datas;

use Illuminate\Support\Str;
use Modules\Xot\Datas\XotData;
use Spatie\LaravelData\Data;

class FirebaseNotificationData extends Data
{
    public string $type; // ' => $data['type'],

    public string $title; // ' => $data['title'],

    public string $body; // ' => $data['body'],

    /** @var array<string, mixed> */
    public array $data; // ' => json_encode($data['data']),

    public static function fromType(string $type): self
    {
        $main_module = Str::of(XotData::make()->main_module)->lower()->toString();
        $data = [
            'type' => $type,
            'title' => trans($main_module.'::notifications.'.mb_strtoupper($type).'.title'),
            'body' => trans($main_module.'::notifications.'.mb_strtoupper($type).'.body'),
<<<<<<< HEAD
<<<<<<< HEAD
            'data' => []];
=======
            'data' => [],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'data' => []];
>>>>>>> a988596b (first)

        return self::from($data);
    }
}
