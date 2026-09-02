<?php

declare(strict_types=1);

namespace Modules\Comment\Datas;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

/**
 * Stato UI autocomplete @ — query + dropdown (risultati restano Collection sul widget).
 */
class MentionSearchWidgetUiData extends Data implements Wireable
{
    use WireableData;

    public string $query = '';

    public bool $showDropdown = false;
}
