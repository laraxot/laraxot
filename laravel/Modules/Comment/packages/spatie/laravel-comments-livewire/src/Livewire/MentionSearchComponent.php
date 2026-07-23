<?php

namespace Spatie\LivewireComments\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\LivewireComments\Support\Config;

class MentionSearchComponent extends Component
{
    public string $query = '';

    public array $results = [];

    public $commentable;

    public function render(): View
    {
        return view('comments::livewire.mention-search', [
            'results' => $this->results,
            'nameField' => Config::commentatorModelNameField(),
        ]);
    }

    #[On('mention-autocomplete-search')]
    public function onSearch($query): void
    {
        $this->query = $query;
        $this->results = array_map(function (CanComment $result) {
            return [
                'id' => $result->getKey(),
                'name' => $result->commentatorProperties()->name,
                'avatar' => $result->commentatorProperties()->avatar,
            ];
        }, Config::resolveMentionsAutocompleteAction()->execute($query, $this->commentable));
    }

    public function select($id, $name): void
    {
        $this->dispatch('mention-selected', [
            'id' => $id,
            'display' => "@$name",
        ]);
    }
}
