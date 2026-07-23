<?php

declare(strict_types=1);

namespace Modules\Comment\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Modules\Comment\Actions\Mention\ResolveMentionsAutocompleteAction;

class MentionSearchComponent extends Component
{
    public string $query = '';

    /** @var Collection<int, Model> */
    public Collection $results;

    public bool $showDropdown = false;

    /** @var array<string, string> */
    protected $listeners = ['mention-search' => 'search'];

    public function mount(): void
    {
        $this->results = new Collection;
    }

    public function search(string $query): void
    {
        $this->query = $query;
        $this->showDropdown = strlen($query) >= 2;

        if (! $this->showDropdown) {
            $this->results = new Collection;

            return;
        }

        $this->results = app(ResolveMentionsAutocompleteAction::class)->execute($query);
    }

    public function selectUser(int $userId): void
    {
        $this->showDropdown = false;
        $this->dispatch('mention-selected', userId: $userId);
    }

    public function render(): View
    {
        /** @var view-string $view */
        $view = 'comment::livewire.mention-search';

        return view($view);
    }
}
