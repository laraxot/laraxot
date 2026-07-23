<?php

declare(strict_types=1);

namespace Themes\Sixteen\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Cms\Datas\BlockData;
use Modules\Cms\Models\Page as PageModel;

class Page extends Component
{
    /** @var array<string, BlockData> */
    public array $blocks = [];

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $side = 'content',
        public string $slug = '',
        public array $data = []
    ) {
        if ($this->slug !== '') {
            $this->blocks = PageModel::getBlocksBySlug($this->slug, $this->side);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('pub_theme::components.page', [
            'side' => $this->side,
            'slug' => $this->slug,
            'data' => $this->data,
            'blocks' => $this->blocks,
        ]);
    }
}
