<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages\Support;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Blog\Actions\Article\ImportArticlesFromByJsonTextAction;

final class ArticleImportActionFactory
{
    public static function make(): Action
    {
        return Action::make('import')
            ->schema([
                FileUpload::make('file')
                    ->label('')
                    ->imagePreviewHeight('250')
                    ->reactive()
                    ->afterStateUpdated(static function (callable $set, TemporaryUploadedFile $state): void {
                        $set('fileContent', File::get($state->getRealPath()));
                    }),
                Textarea::make('fileContent'),
            ])
            ->label('')
            ->tooltip('Import')
            ->icon('heroicon-o-folder-open')
            ->action(static fn (array $data) => app(ImportArticlesFromByJsonTextAction::class)->execute((string) $data['fileContent']));
    }
}
