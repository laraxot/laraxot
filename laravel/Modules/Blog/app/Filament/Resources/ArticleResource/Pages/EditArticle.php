<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Checkbox;
// use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Modules\Blog\Actions\Article\TranslateContentAction;
use Modules\Blog\Filament\Resources\ArticleResource;
use Modules\Blog\Models\Article;
use Modules\Lang\Filament\Resources\Pages\LangBaseEditRecord;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;

class EditArticle extends LangBaseEditRecord
{
    public static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // LocaleSwitcher::make(), // Temporarily disabled until lara-zeus package is working
            'delete' => DeleteAction::make(),
            'translate' => Action::make('translate')
                ->label('Copia Blocchi nelle altre lingue')
                ->tooltip('translate')
                ->icon('heroicon-o-language')
                ->requiresConfirmation()
                ->modalDescription('Assicurati che la versione italiana sia stata settata e salvata')
                ->schema([
                    Checkbox::make('content_blocks')->inline(),
                    Checkbox::make('sidebar_blocks')->inline(),
                    Checkbox::make('footer_blocks')->inline(),
                ])
                ->action(function (Article $record, ArticleResource $articleResource, array $data): void {
                    $locales = $articleResource->getTranslatableLocales();

                    /** @var array<string, mixed> $safeData */
                    $safeData = SafeArrayCastAction::cast($data);

                    app(TranslateContentAction::class)->execute(
                        'article',
                        $record->id,
                        array_values(array_map(fn ($locale) => (string) $locale, $locales)),
                        $safeData,
                        Article::class
                    );
                }),
        ];
    }
}
