<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

use function Safe\preg_replace;

final class DailyNoteForm extends XotBaseResourceForm
{
    protected static string $resource = DailyNoteResource::class;

    /**
     * @return array<string, TextColor>
     */
    public static function richTextColors(): array
    {
        return [
            'slate' => TextColor::make('Grigio', '#475569', darkColor: '#cbd5e1'),
            'blue' => TextColor::make('Blu', '#2563eb', darkColor: '#60a5fa'),
            'green' => TextColor::make('Verde', '#16a34a', darkColor: '#4ade80'),
            'amber' => TextColor::make('Ambra', '#d97706', darkColor: '#fbbf24'),
            'red' => TextColor::make('Rosso', '#dc2626', darkColor: '#f87171'),
            'purple' => TextColor::make('Viola', '#7c3aed', darkColor: '#a78bfa'),
        ];
    }

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('user_id')

                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn (): ?int => request()->is('operator/*') && Auth::id() !== null ? (int) Auth::id() : null)
                            ->disabled(fn (): bool => request()->is('operator/*'))
                            ->dehydrated(),
                        DatePicker::make('note_date')

                            ->required(),
                    ]),
                    RichEditor::make('body_html')

                        ->required()
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike', 'link', 'textColor'],
                            ['blockquote', 'bulletList', 'orderedList'],
                            ['undo', 'redo'],
                        ])
                        ->textColors(self::richTextColors())
                        ->helperText(__('interventions.helpers.daily_note_formatting'))
                        ->columnSpanFull(),
                    FileUpload::make('photo_paths')

                        ->disk('public')
                        ->directory(fn (Get $get): string => self::photoDirectory(
                            userId: self::extractUserId($get),
                            noteDate: $get('note_date'),
                        ))
                        ->image()
                        ->multiple()
                        ->appendFiles()
                        ->maxParallelUploads(1)
                        ->automaticallyResizeImagesMode('contain')
                        ->automaticallyResizeImagesToWidth('1280')
                        ->automaticallyResizeImagesToHeight('1280')
                        ->extraInputAttributes([
                            'accept' => 'image/*',
                            'capture' => 'environment',
                        ], merge: true)
                        ->helperText(__('interventions.helpers.daily_note_photos'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function photoDirectory(int|string|null $userId, mixed $noteDate): string
    {
        $normalizedUserId = max(0, (int) $userId);
        $dateString = self::castMixedToString($noteDate);
        $result = preg_replace('/[^0-9-]/', '', $dateString);
        $normalizedDate = (is_string($result) && $result !== '') ? $result : 'draft';

        return "media/daily-notes/{$normalizedUserId}/{$normalizedDate}/photos";
    }

    private static function extractUserId(callable $get): int
    {
        $userId = $get('user_id');
        if (is_numeric($userId)) {
            return (int) $userId;
        }

        $authId = Auth::id();

        return is_numeric($authId) ? (int) $authId : 0;
    }

    private static function castMixedToString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value) || is_bool($value) || $value === null) {
            return (string) $value;
        }

        return '';
    }
}
