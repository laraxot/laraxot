<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
// use Modules\Blog\Models\Profile;
use Modules\Blog\Models\Profile as BlogProfile;
use Modules\Xot\Actions\Cast\SafeArrayByModelCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Actions\GetViewAction;

/**
 * @property Schema $form
 */
class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    // public Article $article;

    public string $tpl = 'v1';

    // public string $user_id;
    /** @var array<string, mixed> */
    public array $data = [];

    public BlogProfile $model;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.edit-company';

    public function mount(
        BlogProfile $model,
        string $tpl = 'v1',
    ): void {
        $this->model = $model;
        $this->tpl = $tpl;
        $this->data = self::buildFormData($this->model);

        // $this->data['photo_profile'] = $this->model->getFirstMedia('photo_profile');

        // dddx($this->data);

        $this->form->fill($this->data);
    }

    public function render(): View
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $viewParams = [
            'view' => $view,
        ];

        return view((string) $view, $viewParams);
    }

    public function url(): string
    {
        return '#';
    }

    public function form(Schema $form): Schema
    {
        $schema = [];
        foreach (array_keys($this->data) as $key) {
            // dddx([$key, $field, $this->data]);
            // if(gettype($field) == 'float'){
            //     dddx([$key, $field]);
            // }
            $schema[] = TextInput::make($key)
                ->label(SafeStringCastAction::cast($this->data[$key]));
            // ->suffix(fn () => Arr::get($this->data, 'ratings.'.$rating->id.'.value', 0))
            // ->extraInputAttributes(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700 sm:text-sm'])
            // ->disabled()
        }

        // dddx($schema);
        return $form
            ->components($schema)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->model->update($data);

        // $article_aggregate = ArticleAggregate::retrieve($this->article->id);
        // Assert::isArray($ratings_add = $data['ratings_add']);
        // foreach ($ratings_add as $rating_id => $rating) {
        //     $credit = $rating['value'];
        //     if (null != $credit) {
        //         $command = RatingArticleData::from([
        //             'userId' => $this->user_id,
        //             'articleId' => $this->article->id,
        //             'ratingId' => $rating_id,
        //             'credit' => $credit,
        //         ]);

        //         $article_aggregate->rating($command);
        //     }
        // }
    }

    /**
     * @return array<string, mixed>
     */
    private static function buildFormData(BlogProfile $model): array
    {
        $excluded = [
            'id',
            'user_id',
            'created_at',
            'updated_at',
            'updated_by',
            'created_by',
            'deleted_at',
            'deleted_by',
            'slug',
            'extra',
        ];
        $attributes = app(SafeArrayByModelCastAction::class)->execute($model);
        $data = [];
        foreach ($attributes as $key => $value) {
            if (! is_string($key) || in_array($key, $excluded, true)) {
                continue;
            }
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label') ?? '')
                ->submit('save'),
        ];
    }
}
