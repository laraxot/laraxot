<?php

declare(strict_types=1);

namespace Modules\Comment\Datas;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

/**
 * Stato UI singolo commento (FO widget) — un bag Spatie al posto di N proprietà Livewire.
 */
class CommentWidgetUiData extends Data implements Wireable
{
    use WireableData;

    public bool $showAvatar = true;

    public bool $newestFirst = false;

    public bool $writable = true;

    public string $replyText = '';

    public bool $isEditing = false;

    public string $editText = '';

    public bool $showReplies = false;

    public bool $showReactions = false;
}
