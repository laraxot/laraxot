<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Filament\Resources\AbsenceRequestResource;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class AbsenceRequestForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make(__('employee::absence_request.fields.section'))
                ->schema([
                    'user_id' => Select::make('user_id')
                        ->label(__('employee::absence_request.fields.user'))
                        ->relationship('user', 'name')
                        // Chiedere un permesso da telefono: il dipendente e' gia' chi e' collegato, un campo in meno da compilare.
                        ->default(static fn (): int|string|null => Auth::id())
                        ->searchable()
                        ->required()
                        // Il dipendente non sceglie per chi chiede: lo imposta il server (CreateAbsenceRequest).
                        ->visible(static fn (): bool => AbsenceRequestResource::canManageAll()),

                    'type' => Select::make('type')
                        ->label(__('employee::absence_request.fields.type'))
                        ->options([
                            \Modules\Employee\Enums\AbsenceRequestTypeEnum::VACATION->value => __('employee::absence_request.types.vacation'),
                            \Modules\Employee\Enums\AbsenceRequestTypeEnum::LEAVE->value => __('employee::absence_request.types.leave'),
                            \Modules\Employee\Enums\AbsenceRequestTypeEnum::SICK->value => __('employee::absence_request.types.sick'),
                            \Modules\Employee\Enums\AbsenceRequestTypeEnum::INJURY->value => __('employee::absence_request.types.injury'),
                        ])
                        ->required(),

                    'status' => Select::make('status')
                        ->label(__('employee::absence_request.fields.status'))
                        ->options([
                            \Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value => __('employee::absence_request.statuses.pending'),
                            \Modules\Employee\Enums\AbsenceRequestStatusEnum::APPROVED->value => __('employee::absence_request.statuses.approved'),
                            \Modules\Employee\Enums\AbsenceRequestStatusEnum::REJECTED->value => __('employee::absence_request.statuses.rejected'),
                        ])
                        ->default(\Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value)
                        ->required()
                        // Lo stato lo decide chi gestisce le richieste (azioni Approva/Rifiuta): una nuova richiesta nasce sempre in attesa.
                        ->visible(static fn (): bool => AbsenceRequestResource::canManageAll()),

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
