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

    /** Il form di risposta e' aperto: l'editor si monta solo allora, non sotto ogni commento. */
    public bool $isReplying = false;

    /** Il commento e' in modifica; con `isReplying` si escludono a vicenda (il testo sta in `data.text`, non qui). */
    public bool $isEditing = false;

    /** Livello del commento (0 = primo livello): serve a nascondere «Rispondi», l'autorizzazione la ricalcola il server. */
    public int $depth = 0;

    public bool $showReplies = true;

    public bool $showReactions = false;
}
