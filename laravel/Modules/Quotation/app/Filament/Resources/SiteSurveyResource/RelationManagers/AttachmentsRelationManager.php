<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Quotation\Models\SiteSurvey;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Foto, video e note vocali del sopralluogo: sono la sostanza del rilievo, non
 * un accessorio. Allegati gestiti via Spatie MediaLibrary (collection_name
 * come "kind": photos, videos, voice_notes).
 */
final class AttachmentsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'media';

    /** @var array<string, string> */
    private const KIND_TO_COLLECTION = [
        'photo' => 'photos',
        'video' => 'videos',
        'audio' => 'voice_notes',
    ];

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

    private static function collectionToKind(string $collection): string
    {
        /** @var array<string, string> $flipped */
        $flipped = array_flip(self::KIND_TO_COLLECTION);

        return $flipped[$collection] ?? $collection;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'kind' => Select::make('kind')

                ->options(self::kindOptions())
                ->default('photo')
                ->required(),
            'title' => TextInput::make('title')

                ->maxLength(255),
            'path' => FileUpload::make('path')

                ->disk('public')
                ->directory(function (): string {
                    $key = $this->getOwnerRecord()->getKey();

                    return 'site-surveys/'.(is_scalar($key) ? (string) $key : '');
                })
                ->visibility('public')
                ->maxSize(51200)
                ->required()
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('collection_name')

                    ->badge()
                    ->color(fn (string $state): string => match (self::collectionToKind($state)) {
                        'photo' => 'success',
                        'video' => 'info',
                        'audio' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => self::kindOptions()[self::collectionToKind($state)] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')

                    ->formatStateUsing(fn (Media $record): string => $record->name !== '' ? $record->name : $record->file_name)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')

                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('collection_name')

                    ->options([
                        'photos' => __('site_surveys.attachments.kinds.photo'),
                        'videos' => __('site_surveys.attachments.kinds.video'),
                        'voice_notes' => __('site_surveys.attachments.kinds.audio'),
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): Media {
                        $path = $data['path'] ?? null;
                        if (is_array($path)) {
                            $path = reset($path);
                        }
                        $path = is_string($path) ? $path : '';

                        $kind = is_string($data['kind'] ?? null) ? $data['kind'] : 'photo';
                        $collection = self::KIND_TO_COLLECTION[$kind] ?? 'voice_notes';

                        $rawTitle = $data['title'] ?? '';
                        $title = is_string($rawTitle) ? trim($rawTitle) : '';
                        $name = $title !== '' ? $title : basename($path);

                        $userId = Auth::id();

                        /** @var SiteSurvey $survey */
                        $survey = $this->getOwnerRecord();

                        $adder = $survey->addMediaFromDisk($path, 'public')->usingName($name);
                        if (is_numeric($userId)) {
                            $adder = $adder->withCustomProperties(['uploaded_by_user_id' => (int) $userId]);
                        }

                        return $adder->toMediaCollection($collection);
                    }),
            ])
            ->recordActions([
                Action::make('download')

                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Media $record) => Storage::disk((string) $record->disk)
                        ->download($record->getPathRelativeToRoot(), $record->name !== '' ? $record->name : $record->file_name)),
                DeleteAction::make(),
            ]);
    }
}
