<?php

declare(strict_types=1);

namespace Modules\Comment\Datas;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

/**
 * Opzioni display lista commenti — FO CommentsWidget.
 */
class CommentsWidgetUiData extends Data implements Wireable
{
    use WireableData;

    public bool $writable = true;

    public bool $showAvatars = true;

    public bool $notifyOptions = false;

    public bool $newestFirst = false;

    public bool $showReplies = false;

    public bool $showReactions = false;

    public string $notifySubType = '';

    public ?string $noCommentsText = null;

    public string $text = '';
}
