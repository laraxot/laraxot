<?php

declare(strict_types=1);

namespace Modules\UI\View\Components\Render;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Cms\Actions\ResolveLocalizedBlockDataAction;
use Webmozart\Assert\Assert;

/**
 * .
 */
class Block extends Component
{
    /** @var view-string|null */
    public ?string $view = null;

    /**
     * @param  array<string, mixed>  $block
     */
    public function __construct(
        public array $block,
        public ?Model $model = null,
        public string $tpl = '',
    ) {
        $view = Arr::get($this->block, 'data.view', null);
        if ($view === null) {
            /** @var view-string $view */
            $view = 'ui::empty';
        }
        Assert::string($view, __FILE__.':'.__LINE__.' - '.class_basename(self::class));
        /** @var view-string $view */
        $this->view = $view;
    }

    public function render(): ViewFactory|View
    {
        if (! isset($this->block['type'])) {
            /** @var view-string $viewName */
            $viewName = 'ui::empty';

            return view($viewName);
        }

        $view = $this->view ?? 'ui::empty';
        /** @var view-string $view */
        $viewPath = (string) $view;
        if (! view()->exists($viewPath)) {
            $message = 'view not exists ['.$view.'] ! <pre>'.print_r($this->block, true).'</pre>';
            $view_params = [
                'title' => 'deprecated',
                'message' => $message,
            ];
            /** @var view-string $viewAlert */
            $viewAlert = 'ui::alert';

            return view($viewAlert, $view_params);
        }
        $view_params = $this->normalizeViewData($this->block['data'] ?? []);
        $view_params = app(ResolveLocalizedBlockDataAction::class)->execute($view_params);
        $view_params = $this->normalizeViewData($view_params);

        return view($view, $view_params);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeViewData(mixed $data): array
    {
        if (! is_array($data)) {
            return [];
        }

        $viewData = [];

        foreach ($data as $key => $value) {
            if (! is_string($key)) {
                throw new \UnexpectedValueException('Block view data must have string keys.');
            }

            $viewData[$key] = $value;
        }

        return $viewData;
    }
}
