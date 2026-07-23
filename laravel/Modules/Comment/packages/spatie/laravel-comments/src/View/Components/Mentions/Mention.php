<?php

namespace Spatie\Comments\View\Components\Mentions;

use Illuminate\View\Component;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;

class Mention extends Component
{
    public function __construct(protected ?CanComment $mentionee) {}

    public function render()
    {
        return view('comments::components.mentions.mention', [
            'mentionee' => $this->mentionee,
        ]);
    }
}
