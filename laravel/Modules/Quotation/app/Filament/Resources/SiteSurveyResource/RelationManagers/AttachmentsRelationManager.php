<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;

/**
 * Foto, video e note vocali del sopralluogo: sono la sostanza del rilievo, non
 * un accessorio.
 */
final class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('site_surveys.attachments.title');
    }

    /**
     * @return array<string, string>
     */
    private static function kindOptions(): array
    {
        return [
            'photo' => __('site_surveys.attachments.kinds.photo'),
            'video' => __('site_surveys.attachments.kinds.video'),
            'audio' => __('site_surveys.attachments.kinds.audio'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kind')
                ->label(__('site_surveys.attachments.kind'))
                ->options(self::kindOptions())
                ->default('photo')
                ->required(),
            TextInput::make('title')
                ->label(__('site_surveys.attachments.file_title'))
                ->maxLength(255),
            FileUpload::make('path')
                ->label(__('site_surveys.attachments.file'))
                ->disk('public')
                ->directory(fn (): string => 'site-surveys/'.$this->getOwnerRecord()->getKey())
                ->visibility('public')
                ->maxSize(51200)
                ->required()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kind')
                    ->label(__('site_surveys.attachments.kind'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'photo' => 'success',
                        'video' => 'info',
                        'audio' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => self::kindOptions()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('site_surveys.attachments.file_title'))
                    ->formatStateUsing(fn (Media $record): string => (string) ($record->title ?: basename((string) $record->path)))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('site_surveys.attachments.uploaded_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kind')
                    ->label(__('site_surveys.attachments.kind'))
                    ->options(self::kindOptions()),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $path = $data['path'] ?? null;

                        if (is_array($path)) {
                            $path = reset($path);
                        }

                        $data['path'] = is_string($path) ? $path : '';
                        $data['disk'] = 'public';
                        $data['uploaded_by_user_id'] = Auth::id();

                        $title = trim((string) ($data['title'] ?? ''));
                        $data['title'] = $title !== '' ? $title : basename($data['path']);

                        return $data;
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('site_surveys.attachments.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Media $record) => Storage::disk((string) $record->disk)
                        ->download((string) $record->path, (string) ($record->title ?: basename((string) $record->path)))),
                DeleteAction::make(),
            ]);
    }
}
