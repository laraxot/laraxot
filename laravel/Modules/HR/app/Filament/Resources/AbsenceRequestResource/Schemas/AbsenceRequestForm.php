<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class AbsenceRequestForm extends XotBaseResourceForm
{
    protected static string $resource = AbsenceRequestResource::class;

    /** @var array<string, string> */
    private const array ABSENCE_TYPE_ALIASES = [
        'permesso' => 'leave',
        'ferie' => 'vacation',
        'malattia' => 'sick',
        'infortunio' => 'injury',
    ];

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'type_status' => Grid::make(2)->components([
                        'type' => Select::make('type')
                            ->label(__('hr.fields.absence_type'))
                            ->options(self::getAbsenceTypeOptions())
                            ->afterStateHydrated(function (Select $component, ?string $state): void {
                                $component->state(self::normalizeAbsenceType($state));
                            })
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeAbsenceType($state))
                            ->required(),

                        'status' => Select::make('status')
                            ->label(__('hr.fields.status'))
                            ->options([
                                'pending' => __('hr.statuses.pending'),
                                'approved' => __('hr.statuses.approved'),
                                'rejected' => __('hr.statuses.rejected'),
                            ])
                            ->disabled(fn (): bool => request()->is('operator/*')),
                    ]),

                    'dates' => Grid::make(2)->components([
                        'starts_at' => DateTimePicker::make('starts_at')
                            ->label(__('hr.fields.starts_at'))
                            ->required(),
                        'ends_at' => DateTimePicker::make('ends_at')
                            ->label(__('hr.fields.ends_at'))
                            ->required(),
                    ]),

                    'notes' => Textarea::make('notes')
                        ->label(__('hr.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    /** @return array<string, string> */
    private static function getAbsenceTypeOptions(): array
    {
        return [
            'leave' => __('hr::hr.absence_types.leave'),
            'vacation' => __('hr::hr.absence_types.vacation'),
            'sick' => __('hr::hr.absence_types.sick'),
            'injury' => __('hr::hr.absence_types.injury'),
        ];
    }

    private static function normalizeAbsenceType(?string $type): ?string
    {
        if ($type === null || trim($type) === '') {
            return $type;
        }

        $normalized = strtolower(trim($type));

        return self::ABSENCE_TYPE_ALIASES[$normalized] ?? $normalized;
    }
}
