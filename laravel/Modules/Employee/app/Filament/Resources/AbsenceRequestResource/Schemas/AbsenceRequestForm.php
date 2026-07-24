<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class AbsenceRequestForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make(__('employee::absence_request.fields.section'))
                ->schema([
                    'user_id' => Select::make('user_id')
                        ->label(__('employee::absence_request.fields.user'))
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    'type' => Select::make('type')
                        ->label(__('employee::absence_request.fields.type'))
                        ->options([
                            AbsenceRequest::TYPE_VACATION => __('employee::absence_request.types.vacation'),
                            AbsenceRequest::TYPE_LEAVE => __('employee::absence_request.types.leave'),
                            AbsenceRequest::TYPE_SICK => __('employee::absence_request.types.sick'),
                            AbsenceRequest::TYPE_INJURY => __('employee::absence_request.types.injury'),
                        ])
                        ->required(),

                    'status' => Select::make('status')
                        ->label(__('employee::absence_request.fields.status'))
                        ->options([
                            AbsenceRequest::STATUS_PENDING => __('employee::absence_request.statuses.pending'),
                            AbsenceRequest::STATUS_APPROVED => __('employee::absence_request.statuses.approved'),
                            AbsenceRequest::STATUS_REJECTED => __('employee::absence_request.statuses.rejected'),
                        ])
                        ->default(AbsenceRequest::STATUS_PENDING)
                        ->required(),

                    'starts_at' => DateTimePicker::make('starts_at')
                        ->label(__('employee::absence_request.fields.starts_at'))
                        ->required(),

                    'ends_at' => DateTimePicker::make('ends_at')
                        ->label(__('employee::absence_request.fields.ends_at'))
                        ->required(),

                    'notes' => Textarea::make('notes')
                        ->label(__('employee::absence_request.fields.notes'))
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
