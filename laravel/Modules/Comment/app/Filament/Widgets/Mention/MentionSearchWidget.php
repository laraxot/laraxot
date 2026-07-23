<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Mention;

use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Comment\Actions\Mention\ResolveMentionsAutocompleteAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Datas\MentionSearchWidgetUiData;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

class MentionSearchWidget extends XotBaseSchemaWidget
{
    protected static bool $isDiscovered = false;

    // protected string $view = 'comment::filament.widgets.mention.mention-search';

    public MentionSearchWidgetUiData $uiConfig;

    /** @var Collection<int, Model> */
    public Collection $results;

    /** @var array<string, string> */
    protected $listeners = ['mention-search' => 'search'];

    public function __construct()
    {
        $this->uiConfig = MentionSearchWidgetUiData::from([]);
        $this->results = new Collection;
        parent::__construct();
    }

    public function mount(): void
    {
        $this->form->fill([]);
        $this->uiConfig = MentionSearchWidgetUiData::from([]);
        $this->results = new Collection;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [];
    }

    public function search(string $query): void
    {
        $this->uiConfig->query = $query;
        $this->uiConfig->showDropdown = strlen($query) >= 2;

        if (! $this->uiConfig->showDropdown) {
            $this->results = new Collection;

            return;
        }

        $this->results = app(ResolveMentionsAutocompleteAction::class)->execute($query);
    }

    public function selectUser(int $userId): void
    {
        $this->uiConfig->showDropdown = false;

        $nameField = CommentConfigData::make()->models['name'] ?? 'name';
        $user = $this->results->firstWhere('id', $userId);
        $displayName = is_object($user) && isset($user->{$nameField}) && is_string($user->{$nameField})
            ? $user->{$nameField}
            : null;

        $this->dispatch('mention-selected', userId: $userId, displayName: $displayName);
    }
}
