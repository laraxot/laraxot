<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Compliance\Models\CustomerSystem;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Models\Media;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * Schemi tecnici allegati a un impianto: schemi dei quadri elettrici, schemi
 * fotovoltaico, planimetrie videosorveglianza. Riusa il {@see Media} polimorfico
 * con kind = "schema". Accetta PDF, immagini e file CAD (DWG/DXF).
 */
final class SchemasRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'media';

    private const string KIND = 'schema';

    /**
     * PDF + immagini + CAD nativi. I DWG/DXF non hanno un MIME standard univoco e
     * molti browser li inviano come application/octet-stream: la whitelist li copre tutti.
     *
     * @var list<string>
     */
    private const array ACCEPTED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp',
        // DWG
        'image/vnd.dwg',
        'application/acad',
        'application/x-acad',
        'application/dwg',
        'application/x-dwg',
        'drawing/dwg',
        // DXF
        'image/vnd.dxf',
        'application/dxf',
        'application/x-dxf',
        // fallback CAD generico
        'application/octet-stream',
    ];

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Schemi tecnici';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'path' => FileUpload::make('path')
                ->disk('local')
                ->directory(function (): string {
                    $key = $this->getOwnerRecord()->getKey();
                    $keyStr = is_int($key) || is_string($key) ? (string) $key : '0';

                    return 'customer-system-schemas/'.$keyStr;
                })
                ->visibility('private')
                ->acceptedFileTypes(self::ACCEPTED_MIME_TYPES)
                ->maxSize(51200)
                ->storeFileNamesIn('original_name')
                ->required()
                ->columnSpanFull(),
            'original_name' => Hidden::make('original_name'),
            'title' => TextInput::make('title')
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('kind', self::KIND))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->formatStateUsing(fn (Media $record): string => (string) ($record->title ?: basename((string) $record->path)))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mime_type')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('size_bytes')
                    ->formatStateUsing(fn (?int $state): string => $state !== null ? number_format($state / 1024, 0).' KB' : '—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Aggiungi schema')
                    ->using(function (array $data): Media {
                        /** @var CustomerSystem $system */
                        $system = $this->getOwnerRecord();

                        if (is_array($data['path'])) {
                            $pathValue = reset($data['path']);
                            $path = is_string($pathValue) ? $pathValue : '';
                        } else {
                            $path = is_string($data['path']) ? $data['path'] : '';
                        }

                        $original = $data['original_name'] ?? null;
                        if (is_array($original)) {
                            $original = reset($original);
                        }

                        $title = trim(is_string($data['title'] ?? null) ? $data['title'] : '');
                        if ($title === '') {
                            $title = is_string($original) && $original !== '' ? $original : basename($path);
                        }

                        $userId = Auth::id();

                        return app(CreateMediaFromStoredFileAction::class)->execute(
                            mediable: $system,
                            kind: self::KIND,
                            disk: 'local',
                            path: $path,
                            uploadedByUserId: is_numeric($userId) ? (int) $userId : null,
                            title: $title,
                        );
                    }),
            ])
            ->recordActions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Media $record) {
                        $name = $record->title ?: basename((string) $record->path);

                        return Storage::disk((string) $record->disk)->download((string) $record->path, $name);
                    }),
                DeleteAction::make()
                    ->before(function (Media $record): void {
                        Storage::disk((string) $record->disk)->delete((string) $record->path);
                    }),
            ]);
    }
}
